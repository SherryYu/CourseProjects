% Author: Yating Yu
% Date: 10/2017
%
data = csvread('censusdata.csv');
%data = [3,5;2,9;7,10;1,4];
[rows, cols] = size(data);
% data(:,1) = data(:,1)-1790;
sumx = sum(data(:,1));
sumr = sum(log(data(:, 2)));
sumrx = sum(data(:, 1) .* log(data(:, 2)));
sumxsquare = sum(data(:, 1).^2);

A = [rows, -sumx; sumx, -sumxsquare];
r = [sumr; sumrx];
w = A\r;
alpha = exp(w(1));
beta = w(2);

se = sum( (alpha * exp(-beta * data(:, 1)) - data(:, 2)) .^ 2);
logse = sum((log(alpha)-beta * data(:, 1) - log(data(:, 2))) .^ 2);
% for p6, x - 1790
% x = 1 : 1 : 200;
% xlim([1 200]);
x = 1790:1:1990;
xlim([1790 1990]);
figure(1)
y = alpha * exp(-beta * x);
scatter(data(:, 1), data(:, 2))
hold on
plot(x, y, 'r-');
hold off



[p,S] = polyfit(data(:, 1), data(:, 2), 3);
y1 = polyval(p,x);
se2 = sum((polyval(p,data(:,1)) - data(:,2)) .^ 2);
figure(2)
scatter(data(:, 1), data(:, 2))
hold on
plot(x,y1,'r-')
hold off
title('degree 2 ployfit')
