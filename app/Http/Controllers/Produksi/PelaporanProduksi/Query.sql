-- Insert Into StockSC
  (UserName, Process, TransDate, LinkID, Carat, WeightIn, WeightOut, WeightSaldo)
Values
  (:UserName, 'Allocation', :TransDate, :LinkID, :Carat, 0, :WeightOut, :WeightSaldo)


--   
Insert Into BatchCB
(Type, Carat, SWYear, SWMonth, SWOrdinal)
Values
(:Type, :Carat, :SWYear, :SWMonth, 1)


-- 
Update BatchCB
Set SWOrdinal = SWOrdinal + 1
Where SWYear = :SWYear And SWMonth = :SWMonth And Carat = :Carat And Type = :Type