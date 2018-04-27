% Author: Yating Yu
% Date: 10/2017
%
% get correct prediction numbers and preidicting accuracy with different k
function [correctKPredictions, kAccuracy] = getKAccuracy(scaleddata, kvector)
[rows, cols] = size(scaleddata);
kAccuracy = zeros(length(kvector), 1);
correctKPredictions = zeros(length(kvector), 1);
for p = 1 : 3
    k = kvector(p);
    prediction = zeros(rows, 1);
    for i = 1 : rows
        tmptest = scaleddata(i, :);
        dist = zeros(rows,1);
        for j = 1 : rows
            if j ~= i % avoid compute self
                dist(j)=sqrt((tmptest(1)-scaleddata(j,1)).^2+(tmptest(2)-scaleddata(j,2)).^2);
            end
        end
        dist(i) = -1; % avoid return self idx
        [~, smallestkIdx] = getKSmallest(dist, k, 2); % start with 2nd idx, fisrt is -1 set above
        prediction(i) = mode(scaleddata(smallestkIdx, 3));
    end
    correctKPredictions(p) = sum(prediction == scaleddata(:, cols));
    kAccuracy(p) = correctKPredictions(p)/rows;
end
end
