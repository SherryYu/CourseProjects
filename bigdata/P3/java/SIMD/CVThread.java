package SIMD;

import org.apache.spark.ml.classification.LinearSVC;
import org.apache.spark.ml.classification.LinearSVCModel;
import org.apache.spark.ml.evaluation.BinaryClassificationEvaluator;
import org.apache.spark.ml.evaluation.MulticlassClassificationEvaluator;
import org.apache.spark.sql.Dataset;
import org.apache.spark.sql.Row;
import org.apache.spark.sql.SparkSession;

import java.util.ArrayList;
import java.util.List;
import java.util.concurrent.Callable;
import java.util.concurrent.Semaphore;

/**
 * Created by yatingyu on 12/15/17.
 */
public class CVThread implements Callable<Measures> {
    static Semaphore semaphore = new Semaphore(1);
    private final SparkSession spark;
    private final Dataset<Row>[] foldsData;
    private final int foldIndex;

    public CVThread(SparkSession spark, Dataset<Row>[] foldsData, int foldIndex) {
        this.spark = spark;
        this.foldsData = foldsData;
        this.foldIndex = foldIndex;
    }

    @Override
    public Measures call(){
        System.out.printf("Thread %d starts...%n", this.foldIndex + 1);
        List<Row> others = new ArrayList<>();
        Dataset<Row> testSet = null;
        Dataset<Row> training = null;
        try {
            semaphore.acquire();
            testSet = this.foldsData[this.foldIndex];
            // foldsData exclude foldIndex[j]
            for (int j = 0; j < SVM.N_FOLD; j++) {
                if (this.foldIndex != j) others.addAll(this.foldsData[j].collectAsList());
            }
            training = this.spark.createDataFrame(others, testSet.schema());
        } catch (InterruptedException e) {
            e.printStackTrace();
        } finally {
            semaphore.release();
        }

        // train with SVM
        LinearSVC lsvc = new LinearSVC()
            .setRegParam(0.1).setMaxIter(100).setTol(1e-6)
            .setStandardization(true).setFitIntercept(true).setThreshold(0.0)
            .setLabelCol("label").setFeaturesCol("features");
        MulticlassClassificationEvaluator mce = new MulticlassClassificationEvaluator()
            .setLabelCol("label").setPredictionCol("prediction").setMetricName("accuracy");
        BinaryClassificationEvaluator bce = new BinaryClassificationEvaluator()
            .setLabelCol("label").setRawPredictionCol("prediction").setMetricName("areaUnderROC");

        LinearSVCModel lsvcModel = lsvc.fit(training);
        // predict
        Dataset<Row> predictions = lsvcModel.transform(testSet);

        // Get measures
        double accuracy = mce.evaluate(predictions);
        double areaUnderROC = bce.evaluate(predictions);

        Dataset<Row> predictionAndLabels = predictions.select("prediction", "label");

        // confusion matrix
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
}
