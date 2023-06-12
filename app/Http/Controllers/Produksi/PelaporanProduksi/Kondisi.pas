// KONSIDI CARI SPK ==DONE==
Else If ((qryItemProduct.Value = 251) Or (qryItemProduct.Value = 2231)) And (qryItemWeight.Value <> 0) Then // Ktk, KtkCor
    Begin
      If EntryO Then
        Begin
          qryItemWorkOrder.Value := 818; qryItemOSW.Value := 'OStock';
        End
      Else
        Begin
          qryItemWorkOrder.Value := 0; qryItemOSW.Value := 'Stock';
        End;
    End
  Else If (qryItemProduct.Value = 7215) And (qryItemWeight.Value <> 0) Then // Serbuk
    Begin
      If EntryO Then
        Begin
          qryItemWorkOrder.Value := 818; qryItemOSW.Value := 'OStock';
        End
      Else
        Begin
          qryItemWorkOrder.Value := 0; qryItemOSW.Value := 'Stock';
        End;
    End;



// KONSIDI POSTING SPKO ==DONE==
If qryWithdrawOperation.Value In [123, 124, 128] Then
    Begin
      If qryWithdrawOperation.Value = 124 Then qryBatchOrdinal.ParamByName('Type').AsString := 'C'
      Else qryBatchOrdinal.ParamByName('Type').AsString := 'M';
      qryBatchOrdinal.ParamByName('SWYear').AsInteger := qryWithdrawSWYear.Value;
      qryBatchOrdinal.ParamByName('SWMonth').AsInteger := qryWithdrawSWMonth.Value;
      qryBatchOrdinal.ParamByName('Carat').AsInteger := qryWithdrawCarat.Value;
      qryBatchOrdinal.Open; Batch := '';
      If qryBatchOrdinalSWOrdinal.AsString = '' Then
        Begin
          SWOrdinal := 1;
          If qryWithdrawOperation.Value = 124 Then insBatchOrdinal.ParamByName('Type').AsString := 'C'
          Else insBatchOrdinal.ParamByName('Type').AsString := 'M';
          insBatchOrdinal.ParamByName('SWYear').AsInteger := qryWithdrawSWYear.Value;
          insBatchOrdinal.ParamByName('SWMonth').AsInteger := qryWithdrawSWMonth.Value;
          insBatchOrdinal.ParamByName('Carat').AsInteger := qryWithdrawCarat.Value;
          insBatchOrdinal.ExecSQL;
        End
      Else
        Begin
          SWOrdinal := qryBatchOrdinalSWOrdinal.Value + 1;
          If qryWithdrawOperation.Value = 124 Then updBatchOrdinal.ParamByName('Type').AsString := 'C'
          Else updBatchOrdinal.ParamByName('Type').AsString := 'M';
          updBatchOrdinal.ParamByName('SWYear').AsInteger := qryWithdrawSWYear.Value;
          updBatchOrdinal.ParamByName('SWMonth').AsInteger := qryWithdrawSWMonth.Value;
          updBatchOrdinal.ParamByName('Carat').AsInteger := qryWithdrawCarat.Value;
          updBatchOrdinal.ExecSQL;
        End;

      If qryWithdrawOperation.Value = 124 Then Batch := 'BM' + 'C' + qryWithdrawSWYear.AsString + Format('%.*d', [2, qryWithdrawSWMonth.Value]) + qryWithdrawSKU.Value + Format('%.*d', [4, SWOrdinal])
      Else Batch := 'BM' + 'P' + qryWithdrawSWYear.AsString + Format('%.*d', [2, qryWithdrawSWMonth.Value]) + qryWithdrawSKU.Value + Format('%.*d', [4, SWOrdinal]);

      updBatchItem.ParamByName('ID').AsInteger := qryWithdrawID.Value;
      updBatchItem.ParamByName('BatchNo').AsString := Batch;
      updBatchItem.ExecSQL;
      qryBatchOrdinal.Close;
    End
                
Else If qryWithdrawLocation.Value = 7 Then
  Begin
    qryStone.Open;
    If Not qryStone.IsEmpty Then
      Begin
        qrySaldo.Open;
        insStockSC.ParamByName('UserName').AsString := frmMain.UserName;
        insStockSC.ParamByName('TransDate').AsDate := qryWithdrawTransDate.Value;
        insStockSC.ParamByName('LinkID').AsInteger := qryWithdrawID.Value;
        insStockSC.ParamByName('Carat').AsInteger := qryWithdrawCarat.AsInteger;
        insStockSC.ParamByName('WeightOut').AsFloat := qryStoneWeight.Value;
        If qrySaldo.IsEmpty 
          Then insStockSC.ParamByName('WeightSaldo').AsFloat := qryStoneWeight.Value * -1
          Else insStockSC.ParamByName('WeightSaldo').AsFloat := qrySaldoWeightSaldo.Value - qryStoneWeight.Value;
        insStockSC.ExecSQL;
        qrySaldo.Open;
      End;
    qryStone.Close;
  End;




// KONDISI SUSUTAN BESAR DNA KECIL
Procedure CheckPercentage(Percentage : Real);
Begin
  If (qryShrinkShrink.Value / qryAllocationTargetWeight.Value * 100) > Percentage Then // Percentage limit
    Begin
      If (frmMain.UserName = 'Niko') Or (frmMain.UserName = 'Dhora') Then ShowMessage('Nilai Susutan terlalu besar.')
      Else ShowMessage('Nilai Susutan terlalu besar. Hubungi Dhora untuk menyusutkan Nota Terima.');
      If Not (((frmMain.UserName = 'Niko') Or (frmMain.UserName = 'Dhora')) And (MessageDlg('Tetap mau disusutkan ?', mtConfirmation, [mbYes, mbNo], 0) = mrYes)) Then
        btmSave.Enabled := False;
    End;
End;



If qryShrinkLID.Value In [10, 15] Then // Location : QC & PSB
  Begin
    CheckPercentage(3); // 3% Limit
    If qryShrinkLID.Value = 15 Then // Location : PSB
      Begin
        qryPSBSPK.ParamByName('Allocation').AsString := edtAllocation.Text;
        qryPSBSPK.Open;
        If Not qryPSBSPK.IsEmpty Then
          Begin
            ShowMessage('Nilai Susutan tidak sesuai. Hubungi Dhora untuk menyusutkan Nota Terima.');
            If Not ((frmMain.UserName = 'Niko') And (MessageDlg('Tetap mau disusutkan ?', mtConfirmation, [mbYes, mbNo], 0) = mrYes)) Then
              btmSave.Enabled := False;
          End;
        qryPSBSPK.Close;
      End;
  End
Else If qryShrinkLID.Value In [4, 17, 48, 52, 53, 73] Then // Location : GT, Kikir, Bombing, Slep, Brush, Reparasi
    CheckPercentage(10) // 10% Limit
Else If (qryShrinkLID.Value = 49) And (qryShrinkOID.Value In [12, 64]) Then // Slep
  Begin
    CheckPercentage(10);
    lblTime.Visible := True; lblHour.Visible := True; lblMinute.Visible := True;
    edtHour.Visible := True; edtMinute.Visible := True;
    edtHour.Text := '0'; edtMinute.Text := '0';
    edtHour.SetFocus;
  End
Else If qryShrinkLID.Value = 50 Then // Sepuh
  Begin
    qrySepuhSPK.ParamByName('Allocation').AsString := edtAllocation.Text;
    qrySepuhSPK.Open;
    If Not qrySepuhSPK.IsEmpty Then
      Begin
        ShowMessage('Nilai Susutan tidak sesuai. Hubungi Dhora untuk menyusutkan Nota Terima.');
        If Not ((frmMain.UserName = 'Niko') And (MessageDlg('Tetap mau disusutkan ?', mtConfirmation, [mbYes, mbNo], 0) = mrYes)) Then
          btmSave.Enabled := False;
      End;
    qrySepuhSPK.Close;
  End
Else If (qryShrinkLID.Value = 47) And (qryShrinkOID.Value = 22) Then // Enamel
  Begin
    If qryShrinkLID.Value = 50 Then
      CheckPercentage(10); // 10% Limit
    qryPSBSPK.ParamByName('Allocation').AsString := edtAllocation.Text;
    qryPSBSPK.Open;
    If Not qryPSBSPK.IsEmpty Then
      Begin
        ShowMessage('Nilai Susutan tidak sesuai. Hubungi Dhora untuk menyusutkan Nota Terima.');
        If Not ((frmMain.UserName = 'Niko') And (MessageDlg('Tetap mau disusutkan ?', mtConfirmation, [mbYes, mbNo], 0) = mrYes)) Then
          btmSave.Enabled := False;
      End;
    qryPSBSPK.Close;
  End
Else If qryShrinkLID.Value = 12 Then // Location : Poles
  Begin
    btmSave.Enabled := True;
    If qryShrinkOID.Value In [72, 78, 73, 79] Then // Brush poles
          CheckPercentage(7) // 7% Limit
    Else CheckPercentage(6.5); // 6.5% Limit
  End
Else If qryShrinkLID.Value In [7] Then // Location : Cor
  Begin
    {If qryShrinkOID.Value = 15 Then // Operation : Cor
      Begin
        qryTolerance.ParamByName('Operation').AsInteger := qryShrinkOID.Value;
        qryTolerance.ParamByName('Carat').AsInteger := qryShrinkCID.Value;
        qryTolerance.Open;
        If Not qryTolerance.IsEmpty Then
          Begin
            Shrink := qryAllocationWeight.Value * qryToleranceTolerance.Value / 100;
            btmSave.Enabled := True;
            If Shrink < qryShrinkShrink.Value Then
              Begin
                ShowMessage('Toleransi susut lebih kecil dari nilai susutan');
                If Not ((frmMain.UserName = 'Niko') And (MessageDlg('Tetap mau disusutkan ?', mtConfirmation, [mbYes, mbNo], 0) = mrYes)) Then
                  btmSave.Enabled := False;
              End;
          End;
        qryTolerance.Close;
      End
    Else} If qryShrinkOID.Value = 113 Then // Potong Cor
      Begin
        lblEmployeeBatang.Visible := True; edtEmployeeBatang.Visible := True; txtEmployeeBatang.Visible := True;
        lblEmployeeRanting.Visible := True; edtEmployeeRanting.Visible := True; txtEmployeeRanting.Visible := True;
        edtEmployeeBatang.Clear; edtEmployeeRanting.Clear;
        txtEmployeeBatang.Caption := ''; txtEmployeeRanting.Caption := '';
        If qryShrinkShrink.Value > 0.09 Then
          Begin
            ShowMessage('Nilai Susutan terlalu besar !');
            If Not ((frmMain.UserName = 'Niko') And (MessageDlg('Tetap mau disusutkan ?', mtConfirmation, [mbYes, mbNo], 0) = mrYes)) Then
              btmSave.Enabled := False;
          End
//                  CheckPercentage(10); // 10% Limit
      End
    Else CheckPercentage(10); // 10% Limit
  End;



