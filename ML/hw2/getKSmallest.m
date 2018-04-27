% Author: Yating Yu
% Date: 10/2017
%
% get k smallsest distance, starting from index 'startIdx'
function [smallestKVals, smallestkIdx] = getKSmallest(data, k, startIdx)
     [sorted, idx] = sort(data);
     smallestKVals = sorted(startIdx : k + startIdx - 1);
     smallestkIdx = idx(startIdx : k + startIdx - 1);
end
