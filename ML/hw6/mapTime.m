%
% Author: Yating Yu & Yiran Yan
%
% function mapTime() map String format time to double
% Input:
% 1. timestrs: all entries of time strings
% 2. num_egs: number of examples
% 3. idx: specifiy which feature to be processed
%         1 - ViolationTime
%         2 - FromHoursInEffect
%         3 - ToHoursInEffect

% Output: 
% newTime: A double matrix of processed data, size = [num_egs, 1]
%

function newTime = mapTime(timestrs, num_egs, idx)
    newTime = zeros(num_egs, 1);
    for i = 1:num_egs
        tmp_str = timestrs{i, idx};
        if (strcmp(tmp_str{1},'ALL') && idx == 2) 
            newTime(i) = 0;
            continue;
        end
        if (strcmp(tmp_str{1}, 'ALL') && idx == 3) 
            newTime(i) = 1; % time duration one day
            continue;
        end
        % if 'A' or 'P' not specified, assign NaN
        if (size(tmp_str{1}) ~= 5) 
            newTime(i) = NaN;
            continue;
        end
        ap = extractBetween(tmp_str, 5, 5);
        s = str2double(extractBetween(tmp_str{1,1}, 3, 4));
        h = str2double(extractBetween(tmp_str{1,1}, 1, 2));
        
        if ap{1,1} == 'P' && h < 12
            h = h + 12;
        end
        if idx == 1
            newTime(i) = h * 100 + s;
        else
            newTime(i) = datenum(strcat(num2str(h), ':', num2str(s)),'HH:MM');
        end
    end
end
