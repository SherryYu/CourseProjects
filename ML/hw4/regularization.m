% Author: Yating Yu
% Date: 11/2017
%
% try regularization in logistic classifier
w = rand(101, 1) * 0.02 - 0.01;
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

% use regularization
% R(h)=w0^2 + w1^2 +...+wd^2 as described in part1 2(b)
itr = 1;
error = zeros(1000,1);
lamda = 3;
while abs(preErr - err) > 5*1e-6 && itr <= maxIter;
    deltaw = zeros(101, 1);
    preErr = err;
    y = 1 ./ (1 + exp(-x * w));
    for j = 1 : 101
        deltaw(j) = sum( (r - y) .* x(:, j) ) - 2 * lamda * w(j);
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

fprintf('Eta is %f, training(with regularization) done\n', eta);

errx = 1 : 1: itr-1;
plot(errx(1:itr-1), error(1:itr-1), 'r-');
xlabel('epoch');
title('cost');







