% Author: Yating Yu
% Date: 10/2017
%
clc;
clear;
% scale data
traindata = csvread('banknote_train.csv', 1, 0);
maxpoint = max(traindata);
minpoint = min(traindata);
[rows, cols] = size(traindata);
scaleddata = zeros(rows, cols);
for j = 1 : cols - 1
    scaleddata(:,j) = (traindata(:,j)-minpoint(j)) / (maxpoint(j)-minpoint(j));
end
scaleddata(:, cols) = traindata(:, cols);

testdata = csvread('banknote_test.csv');
[rows_t, cols_t] = size(testdata);
maxpoint2 = max(testdata);
minpoint2 = min(testdata);
scaledtestdata = zeros(rows_t, cols_t);
for j = 1 : cols - 1
     scaledtestdata(:,j) = (testdata(:,j)-minpoint2(j)) / (maxpoint2(j)-minpoint2(j));
end
scaledtestdata(:, cols_t) = testdata(:, cols_t);

% find best k
kvector = [3, 9, 99];
[correctKPredictions, kAccuracy] = getKAccuracy(scaleddata, kvector); % get k different accuracies
[maxp, kidx] = max(correctKPredictions);
k = kvector(kidx); % get k with maximum predicting accuracy

% test on test data and get predictions
predictions = getPredictions(scaleddata, scaledtestdata, k);
% actual-prediction pair counts
c0_c0 = length(predictions(predictions == 0 & scaledtestdata(:,3) == 0));
c1_c1 = length(predictions(predictions == 1 & scaledtestdata(:,3) == 1));
c0_c1 = length(predictions(predictions == 1 & scaledtestdata(:,3) == 0));
c1_c0 = length(predictions(predictions == 0 & scaledtestdata(:,3) == 1));
% confusion matrix
confusion = [c0_c0, c0_c1; c1_c0, c1_c1];
accuracy = (c0_c0 + c1_c1) / rows_t * 100;
% consider class 1 as positive
truePositiveRate = c1_c1 / (c1_c1 + c1_c0) * 100;
falsePositiveRate = c0_c1 / (c0_c0 + c0_c1) * 100;


%%%%%%%%%%%%%%%%%%%%
% bivariate Gaussian
%%%%%%%%%%%%%%%%%%%%
c0_data = traindata(traindata(:, cols) == 0, 1:cols - 1);
c1_data = traindata(traindata(:, cols) == 1, 1:cols - 1);
u0 = mean(c0_data);
u1 = mean(c1_data);
sigma0 = getGaussianSigma(c0_data, u0);
sigma1 = getGaussianSigma(c1_data, u1);

g_predictions = zeros(rows_t, 1);
for i = 1 : rows_t
    cur = testdata(i, 1 : cols - 1);
    % maximum likelihood estimates
    ml_c0 = -log(2*pi) - 0.5*log(det(sigma0))-0.5*(cur-u0)*(sigma0^-1)*(cur-u0)';
    ml_c1 = -log(2*pi) - 0.5*log(det(sigma1))-0.5*(cur-u1)*(sigma1^-1)*(cur-u1)';
    if ml_c0 >= ml_c1
        g_predictions(i) = 0;
    else g_predictions(i) = 1;
    end
end
g_accuracy = sum(g_predictions == testdata(:, cols_t)) / rows_t * 100;
g_confusion = [
    length(g_predictions(g_predictions == 0 & testdata(:,3) == 0)), length(g_predictions(g_predictions == 1 & testdata(:,3) == 0));
    length(g_predictions(g_predictions == 0 & testdata(:,3) == 1)), length(g_predictions(g_predictions == 1 & testdata(:,3) == 1))];
