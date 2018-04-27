% Author: Yating Yu
% Date: 09/2017
%
function [likelihood, priors] = trainBayesian(trainData, fVals)

priors.class = unique(trainData.class);
n_class = length(priors.class);
priors.value = zeros(n_class, 1);

% calculate priors
for k = 1 : n_class
    priors.value(k) = (sum(trainData.class == priors.class(k))) / (length(trainData.class));
end

% calculate likelihood
for k = 1 : size(trainData.features, 2)
    feature = fVals{k};
    n_fval = length(feature);
    trainFvals = trainData.features(:, k);
    likelihood.row{k} = priors.class;
    likelihood.col{k} = feature;
    likelihood.values{k} = zeros(n_class, n_fval);
    for j = 1 : n_fval
        curFeature = feature(j);
        for i = 1 : n_class
            curClass = priors.class(i);
            examplesInClass = trainFvals(trainData.class == curClass);
            % add-m smoothing
            likelihood.values{k}(i, j) = (length(examplesInClass(examplesInClass == curFeature)) + 0.1)...
                / (length(examplesInClass) + 0.1 * n_fval);
        end
    end
end

end

