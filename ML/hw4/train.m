% Author: Yating Yu
% Date: 11/2017
%
clc;
clear;
traindata = load('./matlab/train.data');
trainlabel = load('./matlab/train.label');
sparsemat = sparse(traindata(:,1),traindata(:,2),traindata(:,3));
numDocs = length(trainlabel);
numAttr = length(unique(traindata(:,2)));
numSci = sum(trainlabel >= 12 & trainlabel <= 15);
sumEntropy = -numSci/numDocs * log(numSci/numDocs)/log(2) - (numDocs-numSci)/numDocs * log((numDocs-numSci)/numDocs)/log(2);

informationGain = zeros(numAttr, 1);
for i = 1 : numAttr
    N1 = nnz(sparsemat(:, i)); % # of non-zero egs in ith attribute
    [row, ~] = find(sparsemat(:,i));
    nonzerodata = trainlabel(row);
    Nlsci = sum(nonzerodata >= 12 & nonzerodata <= 15);
    Nlzerosci = numSci - Nlsci;
    N2 = numDocs - N1; % # of zero egs in ith attribute
    nonzeroEntropy = 0;
    zeroEntropy = 0;
    if Nlsci > 0 && Nlsci < N1  % avoid 0log0, may get inf
        nonzeroEntropy = -Nlsci/N1 * log(Nlsci/N1)/log(2)- (N1-Nlsci)/N1 * log((N1-Nlsci)/N1)/log(2);
    end
    if Nlzerosci > 0 && Nlzerosci < N2
        zeroEntropy = -Nlzerosci/N2 * log(Nlzerosci/N2)/log(2) - (N2-Nlzerosci)/N2 * log((N2-Nlzerosci)/N2)/log(2);
    end
    informationGain(i) = sumEntropy - N1/numDocs * nonzeroEntropy - N2/numDocs * zeroEntropy;
end

% feature selection
[sorted, itr] = sort(informationGain,'descend');
maxFive = sorted(1:5);
maxFiveIdx = itr(1:5);
featureIdx = itr(1:100);

% logistic regression
w = rand(101, 1) * 0.02 - 0.01; % initialize with random values
eta = 0.001;  % learning rate
x = zeros(numDocs, 101);
x(:, 1) = ones(numDocs, 1);  % x0(t) = 1 for w0
x(:, 2:101) = full(sparsemat(:, featureIdx)); % 100 selected features
r = zeros(numDocs, 1);
r(trainlabel >= 12 & trainlabel <= 15) = 1;  % treat output as binary
y = zeros(numDocs, 1);
err = 0;
preErr = 1;
maxIter = 1000; % maxmium iterations allowed

% repeat until convergence, stop when: 
% 1. difference between previous err and current err <= 5*10^-6
% or 2. exceeds maximum number of iterations
% may get better running time in this way
itr = 1;
error = zeros(1000,1);
while abs(preErr - err) > 5* 1e-6 && itr <= maxIter;
    deltaw = zeros(101, 1);
    preErr = err;
    y = 1 ./ (1 + exp(-x * w));  % sigmoid function
    for j = 1 : 101
        deltaw(j) = sum( (r - y) .* x(:, j) );
    end
    % choose cross-entropy as error function
    err = 0;
    for i = 1 : numDocs
        if y(i) > 0 && y(i) < 1
            err = err - ((r(i) * log(y(i))/log(2)) + ((1 - r(i)) * log(1 - y(i))/log(2)));
        end
    end
    err = err/numDocs;
    error(itr) = err;
    itr = itr + 1;
    w = w + eta * deltaw;
end

fprintf('Eta is %f, training done\n', eta);

errx = 1 : 1: itr-1;
plot(errx(1:itr-1), error(1:itr-1), 'r-');
xlabel('epoch');
title('cost');







