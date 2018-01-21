package SIMD;

/**
 * Created by yatingyu on 12/15/17.
 */
public class Measures {
    private double auROC;
    private double accuracy;
    private double TP;
    private double FP;
    private double FN;
    private double TN;
    public Measures() {
        auROC = 0d;
        accuracy = 0d;
        TP = 0d;
        FP = 0d;
        FN = 0d;
        TN = 0d;
    }

    public Measures(double auROC, double accuracy, double TP, double FP, double TN, double FN) {
        this.auROC = auROC;
        this.accuracy = accuracy;
        this.TP = TP;
        this.FP = FP;
        this.FN = FN;
        this.TN = TN;
    }

    public double getAuROC() {
        return auROC;
    }

    public double getAccuracy() {
        return accuracy;
    }

    public double getTP() { return TP; }

    public double getFP() { return FP; }

    public double getFN() { return FN; }

    public double getTN() { return TN; }
}
