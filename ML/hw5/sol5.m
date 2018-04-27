% load('Yale_64x64.mat')
% [w, v, output, hidden, err_curve]=bp([0 0 ; 0 1; 1 0; 1 1], [0; 1; 1; 0], 2, 4.5, 5000);
% input = [0,0,0,0,0,0,0,1];
% [w, v, output, hidden, err_curve] = bp(input, input, 3, 5.0, 10000);

% x = [-pi:0.1:pi]; 
% fx = (cos(x)+1)/2;
% [ws, vs, outputs, hiddens, err_curves] = bp(x'/pi,fx', 4, 2.0,4000);
% a = sqrt((17-sqrt(17))/34);
% A =[0,6; 6,3];
% [V,D]=eig(A);
% E = eig(A);
% 
% A = [8,5,3; 2,8,10; 6,0,1; 8,2,6];
% B = A - [mean(A);mean(A);mean(A);mean(A)];
% %[X, D] = eig(B);
% [W,Z,eigvals]=pca(B);
% [VB,DB] = eig(cov(B));


faceW = 64;
faceH = 64;
numPerLine = 5;
ShowLine = 5;
Y = zeros(faceH*ShowLine,faceW*numPerLine);
for i=0:ShowLine-1
   for j=0:numPerLine-1
     Y(i*faceH+1:(i+1)*faceH,j*faceW+1:(j+1)*faceW) = reshape(fea(i*numPerLine+j+1,:),[faceH,faceW]);
   end
end
% imagesc(reshape(fea(4,:),[faceH,faceW]));colormap(gray)
imagesc(Y);colormap(gray);
% m = mean(fea);
% meanY = reshape(m,[faceH,faceW]);
% imagesc(meanY);colormap(gray);
% 
% k = 5;
% [W,Z,eigvals]=pca(fea);
% wk = W(:, 1:k);
% zk = Z(:, 1:k);
% zw = (zk * wk');
% X = zw(4,:)+ mean(fea);
% imagesc(reshape(X,[faceH,faceW]));colormap(gray);
% title('image with first 5 principal components');
% zk_four = zk(4, :);


