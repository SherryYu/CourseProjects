%
% Author: Yating Yu & Yiran Yan
%
% function prepareData() pre-process the csv data
% Input: No input
% Output: 
% 1. tr: training data
% 2. tst: tst data
% 3. rows1: number of rows of training data
% 4. cols1: number of columns of training data (# of features + 1)
%
function [tr, tst, rows1, cols1] = prepareData()
    formatSpec = '%f%C%C%{mm/dd/yy}D%C%C%C%f%f%f%f%f%f%q%C%q%q%f%f%C';
    traindata = readtable('parking_train.csv','Delimiter',',','Format',formatSpec);
    temp1 = traindata(:, {'ViolationTime','FromHoursInEffect','ToHoursInEffect'});
    [rows1, ~] = size(traindata);
    vt_col = mapTime(temp1, rows1, 1);
    et_col = (mapTime(temp1, rows1, 3) - mapTime(temp1, rows1, 2)) * 24;
    isOvernight = et_col < 0;
    et_col(isOvernight) = et_col(isOvernight) + 24;
    traindata.ViolationTime = vt_col;
    traindata.Properties.VariableNames{'FromHoursInEffect'} = 'EffectedTime';
    traindata.ToHoursInEffect = [];
    traindata.ViolationTime = vt_col;
    traindata.EffectedTime = et_col;
    traindata.IssueDate = datenum(traindata.IssueDate);

    formatSpec_t = '%f%C%C%{mm/dd/yy}D%C%C%C%f%f%f%f%f%f%q%C%q%q%f%f';
    testdata = readtable('parking_test.csv','Delimiter',',','Format',formatSpec_t);
    temp2 = testdata(:, {'ViolationTime','FromHoursInEffect','ToHoursInEffect'});
    [rows2, ~] = size(testdata);
    vt_col_t = mapTime(temp2, rows2, 1);
    et_col_t = (mapTime(temp2, rows2, 3) - mapTime(temp2, rows2, 2)) * 24;
    isOvernight = et_col_t < 0;
    et_col_t(isOvernight) = et_col_t(isOvernight) + 24;
    testdata.ViolationTime = vt_col_t;
    testdata.Properties.VariableNames{'FromHoursInEffect'} = 'EffectedTime';
    testdata.Properties.VariableNames{'InFrontOfOrOpposite'} = 'FrontOfOrOpposite';
    testdata.ToHoursInEffect = [];
    testdata.ViolationTime = vt_col_t;
    testdata.EffectedTime = et_col_t;
    testdata.IssueDate = datenum(testdata.IssueDate);
    rows = size(traindata, 1);
    num = size(traindata, 2) - 1;
    toDel = NaN(1, num);
    i = 1;
    for j = 1 : num
        if (sum(ismissing(traindata(:, j))) > 0.1 * rows)
            toDel(i) = j;
            i = i + 1;
        end
    end
    toDel = rmmissing(toDel);
    traindata(:, toDel) = [];
    testdata(:, toDel) = [];
    cols1 = size(traindata, 2);
    tr = fillmissing(traindata,'constant',0,'DataVariables',@isnumeric);
    tr = fillmissing(tr,'constant','999','DataVariables',@iscategorical);
    tst = fillmissing(testdata,'constant',0,'DataVariables',@isnumeric);
    tst = fillmissing(tst,'constant','999','DataVariables',@iscategorical);
end