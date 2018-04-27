% Author: Yating Yu
% Date: 10/2017
%
% get covariance matrix of bivariate Gaussian
function sigma = getGaussianSigma(train_data, u)
sums = zeros(2,2);
for i = 1 : size(train_data, 1)
    sums = sums + (train_data(i, :)-u)' * (train_data(i, :)-u);
end
sigma = sums/size(train_data, 1);
end
