% Author: Yating Yu
% Date: 11/2017
%
testdata = load('./matlab/test.data');
testlabel = load('./matlab/test.label');
sparsetest = sparse(testdata(:,1),testdata(:,2),testdata(:,3));
tnumDocs = length(testlabel);
tr = zeros(tnumDocs, 1);
tr(testlabel >= 12 & testlabel <= 15) = 1;
predict = zeros(tnumDocs, 1);

% when gamma = 0.039, recall > 97%
% 0.073 when adding regularization
gamma = 0.5;
tx = zeros(tnumDocs, 101);
tx(:, 1) = ones(tnumDocs, 1);
tx(:, 2:101) = full(sparsetest(:, featureIdx));
for i = 1 : tnumDocs
    y = 1/(1 + exp(-tx(i, :) * w));
    if y >= gamma
        predict(i) = 1;
    end
end

% confusion matrix, racall and precision
% confusion matrix may slighly change due to random initialization of weights 
corrects = sum(predict == tr);
accuracy = sum(predict == tr)/tnumDocs;
confusion = zeros(2,2);
confusion(1,1) = sum(tr == 0 & predict == 0);
confusion(2,2) = sum(tr == 1 & predict == 1);
confusion(1,2) = sum(tr == 0 & predict == 1);
confusion(2,1) = sum(tr == 1 & predict == 0);
recall = confusion(2,2)/(confusion(2,1) + confusion(2,2));
precision = confusion(2,2)/(confusion(1,2) + confusion(2,2));
disp('confusion matrix:')
disp(confusion);
fprintf('accuracy is %.2f%%\n', accuracy*100);
fprintf('recall is %.2f%%\n', recall*100);
fprintf('precision is %.2f%%\n', precision*100);

% 3 attributes with highest magnitude weights
magnw = abs(w(2:101));
[sortmagnw, magnIdx] = sort(magnw, 'descend');
maxThreeWeights = sortmagnw(1:3);
maxThreeIdx = magnIdx(1:3);
topFeature = featureIdx(maxThreeIdx);
vocab = textread('vocabulary.txt','%s');
disp('Top 3 magnatitude weights:')
disp(maxThreeWeights);
disp('Associate weights')
disp(w(maxThreeIdx+1));
disp('Top 3 magnatitude weight feature index:')
disp(maxThreeIdx);
disp('Top 3 magnatitude weight features:')
disp(vocab(topFeature));



