package SIMD;

import org.apache.log4j.Level;
import org.apache.log4j.LogManager;
import org.apache.spark.api.java.JavaRDD;
import org.apache.spark.ml.classification.LinearSVC;
import org.apache.spark.ml.classification.LinearSVCModel;
import org.apache.spark.ml.evaluation.BinaryClassificationEvaluator;
import org.apache.spark.ml.evaluation.MulticlassClassificationEvaluator;
import org.apache.spark.ml.linalg.VectorUDT;
import org.apache.spark.ml.linalg.Vectors;
import org.apache.spark.sql.Dataset;
import org.apache.spark.sql.Row;
import org.apache.spark.sql.RowFactory;
import org.apache.spark.sql.SparkSession;
import org.apache.spark.sql.types.DataTypes;
import org.apache.spark.sql.types.Metadata;
import org.apache.spark.sql.types.StructField;
import org.apache.spark.sql.types.StructType;

import java.util.ArrayList;
import java.util.Arrays;
import java.util.List;
import java.util.concurrent.ExecutionException;
import java.util.concurrent.ExecutorService;
import java.util.concurrent.Executors;
import java.util.concurrent.Future;

/**
 * Created by yatingyu on 12/15/17.
 */
public class SVM {
    protected static final int N_FOLD = 10;
    private final SparkSession spark;
    private Dataset<Row> dataSet;
    private Dataset<Row>[] foldsData;
    private final LinearSVC lsvc;
    private final MulticlassClassificationEvaluator mce;
    private final BinaryClassificationEvaluator bce;

    public SVM() {
        this.spark = SparkSession.builder().master("local[2]").getOrCreate();
        this.spark.sparkContext().setLogLevel("ERROR");
        this.lsvc = new LinearSVC()
            .setRegParam(0.1)
            .setMaxIter(100)
            .setTol(1e-6)
            .setStandardization(true)
            .setFitIntercept(true)
            .setThreshold(0.0)
            .setLabelCol("label")
            .setFeaturesCol("features");
        this.mce = new MulticlassClassificationEvaluator()
            .setLabelCol("label")
            .setPredictionCol("prediction")
            .setMetricName("accuracy");
        this.bce = new BinaryClassificationEvaluator()
            .setLabelCol("label")
            .setRawPredictionCol("prediction")
            .setMetricName("areaUnderROC");
    }

    public SparkSession getSparkSession() { return spark; }

    public Dataset<Row>[] getFoldsData() {
        return foldsData;
    }

    public void readData() {
        JavaRDD<Row> dataRDD = spark.read().option("header", "true")
            .csv("./resources/dataset.csv").javaRDD()
            .map(line -> {
                Double sex = line.get(2).equals("female") ? 1.0d : 2.0d;
                return RowFactory.create(
                    Double.parseDouble(line.get(0).toString()),
                    Vectors.dense(
                        Double.parseDouble(line.get(1).toString()), sex,
                        Double.parseDouble(line.get(3).toString()), Double.parseDouble(line.get(4).toString()))
                );
            });
        StructType schema = new StructType(new StructField[]{
            new StructField("label", DataTypes.DoubleType, false, Metadata.empty()),
            new StructField("features", new VectorUDT(), false, Metadata.empty())
        });
        // create dataframe for train/test
        this.dataSet = spark.createDataFrame(dataRDD, schema);

        double[] weights = new double[N_FOLD];
        Arrays.fill(weights, 1.0d / N_FOLD);
        foldsData = this.dataSet.randomSplit(weights, 111);
    }

    public Measures getMeasuresWithoutCV() {
        Dataset<Row>[] trtst = this.dataSet.randomSplit(new double[]{0.7d, 0.3d},111);
        LinearSVCModel lsvcModel = this.lsvc.fit(trtst[0]);
        // predict
        Dataset<Row> predictions = lsvcModel.transform(trtst[1]);
        // Get measures
        double accuracy = this.mce.evaluate(predictions);
        double areaUnderROC = this.bce.evaluate(predictions);

        // confusion matrix
        Dataset<Row> predictionAndLabels = predictions.select("prediction", "label");
        double TN = predictionAndLabels.toJavaRDD().filter(row ->
            ((Double)row.get(0)).doubleValue() == 0.0d && ((Double)row.get(1)).doubleValue() == 0.0d).count();
        double FP = predictionAndLabels.toJavaRDD().filter(row ->
            ((Double)row.get(0)).doubleValue() == 1.0d && ((Double)row.get(1)).doubleValue() == 0.0d).count();
        double FN = predictionAndLabels.toJavaRDD().filter(row ->
            ((Double)row.get(0)).doubleValue() == 0.0d && ((Double)row.get(1)).doubleValue() == 1.0d).count();
        double TP = predictionAndLabels.toJavaRDD().filter(row ->
            ((Double)row.get(0)).doubleValue() == 1.0d && ((Double)row.get(1)).doubleValue() == 1.0d).count();
        return new Measures(areaUnderROC, accuracy, TP, FP, TN, FN);
    }

    public static void main(String[] args) {
        LogManager.getLogger("org").setLevel(Level.ERROR);
        SVM svm = new SVM();
        svm.readData();

        // without CV
        Measures m = svm.getMeasuresWithoutCV();
        double acc = m.getAccuracy();
        double areaUnderROC = m.getAuROC();
        System.out.println("1. Without CV......");
        System.out.printf("accuracy = %f%n", acc);
        System.out.printf("area under ROC = %f%n", areaUnderROC);
        System.out.printf("TN = %f, FP = %f%n", m.getTN(), m.getFP());
        System.out.printf("FN = %f, TP = %f%n", m.getFN(), m.getTP());
        System.out.println();

        long startTime=System.currentTimeMillis();
        // thread pool (with CV)
        System.out.println("2. With CV using threads......");
        ExecutorService exec = Executors.newCachedThreadPool();
        List<Future<Measures>> measuresList = new ArrayList<>();
        Dataset<Row>[] foldDataArray = svm.getFoldsData();
        for(int foldIdx = 0; foldIdx < N_FOLD; foldIdx++){
            measuresList.add(exec.submit(new CVThread(svm.getSparkSession(), foldDataArray, foldIdx)));
        }
        double totalAcc = 0d, totalAUC = 0d;
        double totalTP = 0d, totalFP = 0d, totalTN = 0d, totalFN = 0d;
        for(Future<Measures> future : measuresList){
            try {
                Measures measure = future.get();
                double accuracy = measure.getAccuracy();
                double auc = measure.getAuROC();
                System.out.printf("accuracy = %f%n", accuracy);
                System.out.printf("area under ROC = %f%n", auc);
                System.out.printf("TN = %f, FP = %f%n", measure.getTN(), measure.getFP());
                System.out.printf("FN = %f, TP = %f%n", measure.getFN(), measure.getTP());
                System.out.println();
                totalAcc += accuracy;
                totalAUC += auc;
                totalTP += measure.getTP();  totalFP += measure.getFP();
                totalTN += measure.getTN(); totalFN += measure.getFN();
            }catch(ExecutionException | InterruptedException e){
                e.printStackTrace();
            }finally{
                exec.shutdown();
            }
        }
        System.out.printf("Mean Accuracy = %f%n", totalAcc/ N_FOLD);
        System.out.printf("Mean AUC = %f%n", totalAUC/ N_FOLD);
        System.out.printf("Mean TN = %f%n", totalTN / N_FOLD);
        System.out.printf("Mean FP = %f%n", totalFP / N_FOLD);
        System.out.printf("Mean FN = %f%n", totalFN / N_FOLD);
        System.out.printf("Mean TP = %f%n", totalTP / N_FOLD);

        long endTime = System.currentTimeMillis();
        System.out.printf("%nTime: %fs%n",(endTime - startTime)/1000d);
    }
}


