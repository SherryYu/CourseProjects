% Author: Yating Yu
% Date: 10/2017
%
% get predictions on test data
function [predictions] = getPredictions(scaleddata, scaledtestdata, k)
[rows, cols] = size(scaleddata);
[rows_t, ~] = size(scaledtestdata);
predictions = zeros(rows_t, 1);
for t = 1 : rows_t
    tmptest = scaledtestdata(t, :);
    dist = zeros(rows,1);
    for i = 1 : rows
        dist(i)=sqrt((tmptest(1)-scaleddata(i,1)).^2+(tmptest(2)-scaleddata(i,2)).^2);
    end
    % get index of k neighbors
    [~, smallestkIdx] = getKSmallest(dist, k, 1);
    predictions(t) = mode(scaleddata(smallestkIdx, cols)); % majority vote
end
end
