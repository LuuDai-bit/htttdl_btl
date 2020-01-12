CREATE TABLE income 
(vung varchar, nam_2010 int,nam_2012 int, nam_2014 int,nam_2016 int);

COPY income FROM 'E:/Data/income.csv' WITH (FORMAT csv);