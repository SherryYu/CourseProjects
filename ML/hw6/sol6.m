%
% Author: Yating Yu
%

warning off;
clear;
clc;
%%%%%%%%%%%%%%%
% prepare data
%%%%%%%%%%%%%%%
[tr, tst, rows1, cols1] = prepareData();

%%%%%%%%%%%%%%%%%%%%%%%%%%
% experiment on algorithms
%%%%%%%%%%%%%%%%%%%%%%%%%%
rng(500,'twister');
cvp = cvpartition(rows1, 'Kfold', 10);
nbaccuracies = zeros(10,1);
dtaccuracies = zeros(10,1);
fprintf('1. Trying NaiveBayes and Decision Tree on ALL features with 10-fold Cross Validation...\n');
for i = 1 : 10
    foldIdx = test(cvp, i);
    cvtest = tr(foldIdx, :);
    cvtrain = tr(~foldIdx, :);
    % decision tree
    dtMdl = fitctree(cvtrain, 'ViolationCode');
    dtprelabels = predict(dtMdl, cvtest);
    dtaccuracies(i) = sum(dtprelabels == cvtest.ViolationCode)/length(dtprelabels);
 
    % naive bayes
    nbMdl = fitcnb(cvtrain, 'ViolationCode','Distribution','mvmn');
    nbprelabels = predict(nbMdl, cvtest);
    nbaccuracies(i) = sum(nbprelabels == cvtest.ViolationCode)/length(nbprelabels);
end
fprintf('NB Accuracies:\n');
disp(nbaccuracies');
fprintf('Decision Tree Accuracies:\n');
disp(dtaccuracies');
nb_mean_acc = mean(nbaccuracies);
dt_mean_acc = mean(dtaccuracies);
fprintf('Naive Bayes Mean Accuracy: %f\n', nb_mean_acc);
fprintf('Decision Tree Mean Accuracy: %f\n\n', dt_mean_acc);

%%%%%%%%%%%%%%%%%%%%
% feature selection
%%%%%%%%%%%%%%%%%%%%
fprintf('2. Feature Selection with Random Forest...\n');
numTrees = 100;
% get feature importance using random forest
tbModel = TreeBagger(numTrees,tr,'ViolationCode','OOBPredictorImportance','On',...
    'Method','classification');
imp = tbModel.OOBPermutedPredictorDeltaError;
[sorted, sidx] = sort(imp, 'descend');
figure;
bar(imp);
title('Feature Importance');
ylabel('Predictor importance estimates');
xlabel('Predictors');
h = gca;
h.XTick = 1:cols1-1;
h.XTickLabel = tbModel.PredictorNames;
h.XTickLabelRotation = 45;
h.TickLabelInterpreter = 'none';

fprintf('Selected Features:');
selectedIdx = sidx(1:10); % select top 10 features
disp(tbModel.PredictorNames(selectedIdx));
fsData = tr(:, selectedIdx); % selected 10 columns
fsData = [fsData, tr(:, cols1)];
cvp = cvpartition(size(tr, 1), 'Kfold', 10);
rfaccuracies = zeros(10,1);
fprintf('3. Cross Validation of Final Method...\n');
fprintf('%d features, %d trees.\n', length(selectedIdx), numTrees);
% 10-fold cross validation for the classifier
for i = 1 : 10
    foldIdx = test(cvp, i);
    cvtest = fsData(foldIdx, :);
    cvtrain = fsData(~foldIdx, :);
    fsmdl = TreeBagger(numTrees,cvtrain,'ViolationCode',...
    'Method','classification'); 
    pre = predict(fsmdl, cvtest);
    rfaccuracies(i) = sum(pre == cvtest.ViolationCode)/length(pre);
end
fprintf('Accuracies of Random Forest With Selected Feature:\n');
disp(rfaccuracies');
rf_mean_acc = mean(rfaccuracies);
fprintf('Random Forest Mean Accuracy: %f\n\n', rf_mean_acc);

%%%%%%%%%%%%
% predict
%%%%%%%%%%%%

fprintf('4. Predict...\n');
finalTrain = tr(:, selectedIdx);
finalTrain = [finalTrain, tr(:, cols1)];
finalTest = tst(:, selectedIdx);
Mdl = TreeBagger(numTrees, finalTrain,'ViolationCode',...
    'Method','classification');
predictedLabels = predict(Mdl, finalTest);

result = tst(:, 1);
result.Violation = predictedLabels;
% writetable(result, '~/Desktop/predictions.csv');

fprintf('Done.\n');
