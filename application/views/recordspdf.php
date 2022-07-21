<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Manrope:wght@300&family=Simonetta:ital@1&display=swap" rel="stylesheet">
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<body style="position:relative; border:1px solid #3B525D; margin:0;padding:0;font-family: 'Manrope', sans-serif;">
<table style="width:100%;margin-left:10px;" cellspacing="0" cellpadding:"0";>
    <tr>
        <td width="60%">
            <table>
                <tr>
                    <td width="100px" valign="top"><p style="margin:0;color:#37474F; font-style: normal;font-size: 16px;line-height: 22px;">Salon Name</p></td>
                    <td width="20px" valign="top">:</td>
                    <td valign="top"><p style="font-family: 'Manrope';margin:0;color:#37474F; font-style: normal;font-size: 16px;line-height: 22px;letter-spacing: 0.2px;"><?php echo $records['salon_name']; ?></p></td>
                </tr>
                
                <tr>
                    <td valign="top" ><p style="margin:0;color:#37474F; font-style: normal;font-size: 16px;line-height: 22px;">Address</p></td>
                    <td width="20px" valign="top">:</td>
                    <td valign="top"><p style="font-family: 'Manrope';margin:0;color:#37474F; font-style: normal;font-size: 16px;line-height: 22px;letter-spacing: 0.2px;"><?php echo $records['salon_address']; ?></p></td>
                </tr>
                
                <tr>
                    <td valign="top"><p style="margin:0;color:#37474F; font-style: normal;font-size: 16px;line-height: 22px;">Phone No</p></td>
                    <td width="20px" valign="top">:</td>
                    <td valign="top"><p style="font-family: 'Manrope';margin:0;color:#37474F; font-style: normal;font-size: 16px;line-height: 22px;letter-spacing: 0.2px;"><?php echo $records['salon_phone']; ?></p></td>
                </tr>
                
                <tr>
                    <td valign="top"><p style="margin:0;color:#37474F; font-style: normal;font-size: 16px;line-height: 22px;">Email</p></td>
                    <td width="20px" valign="top">:</td>
                    <td valign="top"><p style="font-family: 'Manrope';margin:0;color:#37474F; font-style: normal;font-size: 16px;line-height: 22px;letter-spacing: 0.2px;"><?php echo $records['salon_email']; ?></p></td>
                </tr>
            </table>
        </td>
        <td width="40%">
            
            <table style="width:100%">
            <tr style="text-align:center;width:100%;">
                <td  valign="top" colspa="3" style="text-align:center;width:100%;">
                    <div style="text-align:center;width:100%;">
                        <img src="https://my-salon-app.surbhiinfotech.com/assets/logo1.png">
                    </div>
                </td>
            </tr>
                <tr>
                    <td><p style="margin:0;margin-left:15px;color:#37474F; font-style: normal;font-size: 16px;line-height: 22px;">Invoice From Date : <span style="font-family: 'Manrope';color:#52636B;font-weight: 300;font-size: 16px;line-height: 22px;letter-spacing: 0.2px;"><?php echo date("d M Y", strtotime($records['start_date'])); ?></span></p></td>
                    <!--<td></td>-->
                </tr>
                <tr>
                    <td><p style="margin:0;margin-left:15px;color:#37474F; font-style: normal;font-size: 16px;line-height: 22px;">Invoice To Datee : <span style="font-family: 'Manrope';color:#52636B;font-weight: 300;font-size: 16px;line-height: 22px;letter-spacing: 0.2px;"><?php echo date("d M Y", strtotime($records['end_date'])); ?></span></p></td>
                    <!--<td></td>-->
                </tr>
            </table>
        </td>
    </tr>
</table>


<table style="margin-top:50px;" width="100%" cellspacing="0" cellpadding:"0";>
    <tr style="background-color:#3B525E;width:100%;padding:12px;color:#fff;">
        <th width="100px">Invoice Number</th>
        <th>Client Name</th>
        <th>Earned Amount</th>
        <th width="100px">Commission Charges</th>
        <th>Mode</th>
        <th>Date & Time</th>
    </tr>
    <?php $count = 0; foreach($records['all_data'] as $record) { $count++;?>
    <tr style="color:#555555;text-align:center; background-color:<?php if($count % 2 == 1){ echo '#F8F8F8'; }else { echo '#fff'; }?>">
        <td style="padding:8px;">INV_<?php echo $record['id']; ?></td>
        <td><?php echo $record['user_name']; ?></td>
        <td><?php echo $record['amount']; ?></td>
        <td><?php echo $record['commission_charges']; ?></td>
        <td><?php echo $record['payment_type']; ?></td>
        <td><?php echo $record['date']; ?></td>
    </tr>
    <?php }?>
</table>
<div style="position:absolute; width:97.5%; top:95%;padding:8px; text-align:center; background-color:#F2F2F2; color:#37474F;font-family: 'Simonetta', cursive;">
    Thank You
</div>
</body>