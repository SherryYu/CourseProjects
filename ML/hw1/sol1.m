% Author: Yating Yu
% Date: 09/2017
%
clc;
clear;
data1 = csvread('phish_train.csv');
trainData.class = data1(:,10);
trainData.features = data1(:,1:9);
data2 = csvread('phish_test.csv');
testData.class = data2(:,10);
testData.features = data2(:,1:9);

featureVals={[1,-1,0];[-1,0,1];[1,-1,0];
    [-1,0,1];[-1,0,1];[1,0,-1];
    [1,-1,0];[1,-1];[0,1]};

[likelihood, priors] = trainBayesian(trainData, featureVals);
accuracy_test = testBayesian(testData, priors, likelihood);
accuracy_train = testBayesian(trainData, priors, likelihood);

%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%
% prior for class 1
prior_class1 = priors.value(priors.class == 1);
fprintf('1. prior for class 1 is %.4f.\n', prior_class1);

% p(x|C=0), each cell is likelihood for each feature given C = 0
% sequence of columns in each cell is corresponding to [featureVals] above
tmp = cell(1,9);
for i = 1 : 9
    tmp{i} = likelihood.values{i}(likelihood.row{i}(:) == 0, :);
end;
fprintf('2. likelihood for class 0 is:\n');
likelihood_class0 = cell2mat(tmp)

% accuracy on test set, percentage
test_acc = round(accuracy_test * 100, 2);
fprintf('3. accuracy on test set is %.2f%%.\n', test_acc);

% accuracy on training set, percentage
train_acc = round(accuracy_train * 100, 2);
fprintf('4. accuracy on training set is %.2f%%.\n', train_acc);

% % zero-R will predict -1 for all cases
zerorPrediction = ones(size(data2,1), 1) * -1;
simpleAccuracy_test = sum(zerorPrediction == testData.class) / length(testData.class);
zerorPrediction2 = ones(size(data1,1), 1) * -1;
simpleAccuracy_train = sum(zerorPrediction2 == trainData.class) / length(trainData.class);
fprintf('5. Zero-R accuracy on test set is %.2f%%.\n', simpleAccuracy_test*100);
fprintf('6. Zero-R accuracy on training set is %.2f%%.\n', simpleAccuracy_train*100);
