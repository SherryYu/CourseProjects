% Author: Yating Yu
% Date: 09/2017
%
function [accuracy] = testBayesian(testData, priors, likelihood)

rows = size(testData.features, 1);
n_class = length(priors.class);

posterior.class = priors.class;
posterior.value = zeros(rows, n_class);
prediction = zeros(rows, 1);
% predict class for every test case using MAP
for i = 1 : rows
    curLine = testData.features(i, :);
    for j = 1 : n_class
        curClass = priors.class(j);
        logsum = log(priors.value(priors.class == curClass));% logP(C)
        for k = 1 : length(curLine)
            fval = curLine(k);
            % P(x=fval | C=curClass)
            curLikelihood = likelihood.values{k}(j, likelihood.col{k}(:) == fval);
            logsum = logsum + log(curLikelihood);
        end
        posterior.value(i, j) = logsum;
    end  
    % predict class
    map = max(posterior.value(i, :));
    prediction(i) = posterior.class(posterior.value(i, :) == map);  
end

accuracy = sum(prediction == testData.class) / length(testData.class);
end
