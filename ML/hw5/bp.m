%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%
%
% function [w, v, output, hidden, err_curve] 
%	= bp(I,D,n_hidden,eta,n_max)
%
%       Backprop training for neural net with 1 hidden layer, using stochastic gradient descent.  All nodes compute the sigmoid function.
%
%	arguments:
%
%		I: input matrix, with each row being an input vector
%
%		+---------------+
%		|      x1       |
%		+---------------+
%		|      x2       |
%		+---------------+
%		|      x3       |
%		+---------------+
%		|      x4       |
%		+---------------+
%
%		D: output matrix, with each row being an output vector
%
%		+---------------+
%		|      D1       |
%		+---------------+
%		|      D2       |
%		+---------------+
%		|      D3       |
%		+---------------+
%		|      D4       |
%		+---------------+
%
%		n_hidden: number of hidden layer nodes
%
%		eta: learning rate
%	
%		n_max: number of epochs to train
%
%		
%
%
%	returned values:
%
%		w: input-to-hidden weight matrix, giving
%                       weights for the connections from the inputs
%                       to the hidden nodes, with each column
%			holding the weights for a single hidden node.
%			Last row is for the bias (aka dummy) weight
%
%		v: hidden-to-output weight matrix, giving weights
%                       for the connections from the hidden nodes
%                       to the output nodes with each column
%			holding the weights for a single output node.
%			Last row is for the bias (aka dummy) weight 
%
%		output: matrix holding all output vectors for all input
%			examples. Each row holds the output vector
%			for each example in the matrix I.
%
%		hidden: matrix holding all hidden vectors for all input
%			examples. Each row holds the hidden vector z
%			for each input example in the matrix I.
%			
%		err_curve: matrix holding squared error 1/2(r-y)^2
%                       in each output unit
%			for all epochs. Each column corresponds to
%                       an output unit.  Each row holds the
%			error vector (average squared error over all examples) 
%                       for that epoch.
%
%			mean(err_curve') gives the overall mean error.
%	
% Examples:
%
% 1. XOR
%
% [w, v, output, hidden, err_curve] = bp([0 0 ; 0 1; 1 0; 1 1], [0; 1; 1; 0], 2, 4.5, 5000); 
%
% 2. autoassociation
%
% [w, v, output, hidden, err_curve] = bp(eye(8,8), eye(8,8), 3, 5.0, 10000);
%
% 3. function approximation
%
% x = [-pi:0.1:pi]; fx = (cos(x)+1)/2;
% [ws, vs, outputs, hiddens, err_curves] = bp(x'/pi,fx', 4, 2.0,4000);
%
% Original Author: Yoonsuck Choe 
% http://faculty.cs.tamu.edu
% Sat Feb  9 16:14:37 CST 2008
% License: GNU public license (http://www.gnu.org)
%
% Modified extensively by Prof. Lisa Hellerstein 
% for CS6923, Machine Learning, NYU Tandon School of Engineering
%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%

function [w, v, output, hidden, err_curve] = bp(I,D,n_hidden,eta,n_max)

%----------------------------------------
% 1. Setup network weights
%----------------------------------------

[r_inp,c_inp] = size(I);
n_examples = r_inp;	   	% number of input examples
n_input    = c_inp;             % number of attributes in each example
n_hidden   = n_hidden;          % number of hidden notes in network

[r_out,c_out] = size(D);
n_output   = c_out;             % number of outputs for each input example

rng('default');                 % Initialize random number generator in a way that produces same numbers
rng(1,'twister');               % each time.  Good for debugging and checking correctness of program.

w = rand(n_input+1,n_hidden);   % Initialize w weights to random values (including bias weight w0)
v = rand(n_hidden+1,n_output);  % Initialize v weights to random values (including bias weight v0)

%----------------------------------------
% 3. Initialize error curve matrix.
%    Each output unit will have a separate error curve.
%    Each iteration is stored in one row.
%----------------------------------------
err_curve = zeros(n_max,c_out);

% 4. Main loop
for n=1:n_max             % each iteration is an epoch

  output = [];
  hidden = [];

  sq_err_sum = zeros(n_output,1);
  for k=1:n_examples 
	
    %--------------------
    % 1. activate
   %--------------------
    x = [I(k,:),1]';   % Note that the '1' at the end of [I(k,:),1] is the dummy input attribute x_0
    z = [sigmoid(w'*x);1]; % the sigmoid function is defined in sigmoid.m
    hidden = [hidden; z'];
    y = sigmoid(v'*z);
    output = [output; y'];

    %--------------------
    % 2. calculate error
    %--------------------
    err = D(k,:)'-y;
    sq_err_sum = sq_err_sum + (1/2)*err.^2;


    %--------------------
    % 3. calculate Delta_v
    % Doing gradient descent with respect to squared error function
    % (1/2)*(r-y)^2, where r is the correct output and y is the prediction,
    % and using fact that all nodes compute the sigmoid function.
    %--------------------
    
    Delta_v = eta*z*(err.*y.*(1-y))';


    %--------------------
    % 3. calculate Delta_w 
    %--------------------


v1 = v(1:end-1,:);
z1 = z(1:end-1,:);
Delta_w = eta*x*(v1*(err.*y.*(1-y)).*(1-z1).*z1)';

    v = v + Delta_v;
    w = w + Delta_w;
    
  end

  err_curve(n,:) = sq_err_sum/n_examples;

  % print out progress
  fprintf('epoch %d: err %f\n',n,mean(sq_err_sum)/n_examples);
  
end

% plot the error curve 
if n_output == 1
	plot(1:n_max,err_curve);  
else plot(1:n_max,mean(err_curve'));

end


