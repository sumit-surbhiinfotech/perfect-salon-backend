<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Manrope:wght@300&family=Simonetta:ital@1&display=swap" rel="stylesheet">
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<body style="position:relative; border:1px solid #3B525D; margin:0;padding:0;font-family: 'Manrope', sans-serif;">
<table style="width:100%;margin-left:10px;" cellspacing="0" cellpadding:"0";>
    <tr>
        <td width="60%" style="">
            <table style="width:100%;">
                <tr>
                    <td width="100px" valign="top"><p style="margin:0;color:#37474F; font-style: normal;font-size: 16px;line-height: 22px;">Salon Name</p></td>
                    <td width="20px" valign="top">: </td>
                    <td valign="top"><p style="font-family: 'Manrope';margin:0;color:#37474F; font-style: normal;font-size: 16px;line-height: 22px;letter-spacing: 0.2px;"><?php echo $salon_name;?></p></td>
                </tr>
                
                <tr>
                    <td valign="top" ><p style="margin:0;color:#37474F; font-style: normal;font-size: 16px;line-height: 22px;">Address</p></td>
                    <td width="20px" valign="top">:</td>
                    <td valign="top"><p style="font-family: 'Manrope';margin:0;color:#37474F; font-style: normal;font-size: 16px;line-height: 22px;letter-spacing: 0.2px;"><?php echo $address?></p></td>
                </tr>
                
                <tr>
                    <td valign="top"><p style="margin:0;color:#37474F; font-style: normal;font-size: 16px;line-height: 22px;">Phone No</p></td>
                    <td width="20px" valign="top">:</td>
                    <td valign="top"><p style="font-family: 'Manrope';margin:0;color:#37474F; font-style: normal;font-size: 16px;line-height: 22px;letter-spacing: 0.2px;"><?php echo $phone ?></p></td>
                </tr>
                
                <tr>
                    <td valign="top"><p style="margin:0;color:#37474F; font-style: normal;font-size: 16px;line-height: 22px;">Email</p></td>
                    <td width="20px" valign="top">:</td>
                    <td valign="top"><p style="font-family: 'Manrope';margin:0;color:#37474F; font-style: normal;font-size: 16px;line-height: 22px;letter-spacing: 0.2px;"><?php echo $email?></p></td>
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
                    <td><p style="margin:0;margin-left:15px;color:#37474F; font-style: normal;font-size: 16px;line-height: 18px;">Invoice Number : <span style="font-family: 'Manrope';color:#52636B;font-weight: 300;font-size: 16px;line-height: 22px;letter-spacing: 0.2px;"><?php echo $invoice_id; ?></span></p></td>
                    <!--<td></td>-->
                </tr>
                <tr>
                    <td><p style="margin:0;margin-left:15px;color:#37474F; font-style: normal;font-size: 16px;line-height: 18px;">Invoice To Date : <span style="font-family: 'Manrope';color:#52636B;font-weight: 300;font-size: 16px;line-height: 22px;letter-spacing: 0.2px;"><?php echo date('d M Y', strtotime($booking_date)); ?></span></p></td>
                    <!--<td></td>-->
                </tr>
                 <tr>
                    <td><p style="margin:0;margin-left:15px;color:#37474F; font-style: normal;font-size: 16px;line-height: 18px;">Order No : <span style="font-family: 'Manrope';color:#52636B;font-weight: 300;font-size: 16px;line-height: 22px;letter-spacing: 0.2px;"><?php echo $order_no; ?></span></p></td>
                    <!--<td></td>-->
                </tr>
            </table>
        </td>
    </tr>
</table>

<hr style="border:2px double #798B94;"/>
<div style="margin-left:10px;">
     <p style="margin:0;color:#37474F; font-style: normal;font-size: 16px;line-height: 22px;">Customer Name   : <span style="font-family: 'Manrope';color:#52636B;font-weight: 300;font-size: 16px;line-height: 22px;letter-spacing: 0.2px;"><?php echo $user_name; ?></span></p>
     <p style="margin:0;color:#37474F; font-style: normal;font-size: 16px;line-height: 22px;">Phone Number   : <span style="font-family: 'Manrope';color:#52636B;font-weight: 300;font-size: 16px;line-height: 22px;letter-spacing: 0.2px;"><?php echo $user_phone; ?></span></p>
</div>
<!--$user_name-->
<table style="margin-top:40px;" width="100%" cellspacing="0" cellpadding:"0";>
    <tr style="background-color:#3B525E;width:100%;padding:12px;color:#fff;">
        <th width="200px">Descripation</th>
        <th width="100px">Timeslot</th>
        <th width="100px">Toatl Amount</th>
    </tr>
    <?php $total_price = 0; $commission_price = 0;
          foreach($services as $service) { 
          $total_price += $service['service_price'];
          $commission_price += (int)($service['service_price'] - (int)($service['service_price'] * 4 / 100));
    ?>
    <tr style="color:#555555;text-align:center; background-color:#F8F8F8; margin-botton:8px;">
        <td style="padding:8px;"><?php echo $service['service_name']; ?></td>
        <td><?php echo $service['booking_time']; ?></td>
        <td>₹ <?php echo $service['service_price']; ?></td>
    </tr>
    <?php }?>
</table>
<div style="text-align:right;margin-top:30px;">
    <table align="right">
        <tr>
            <td width="200px"><p style="margin:0;color:#37474F; font-style: normal;font-size: 16px;line-height: 22px;">Basic Amount </p></td>
            <td width="20px">:</td>
            <td width="100px"><span style="margin:0;color:#52636B; font-style: normal;font-size: 15px;line-height: 22px;">₹ <?php echo $commission_price; ?></span></td>
        </tr>
        <tr>
            <td width="200px"><p style="margin:0;color:#37474F; font-style: normal;font-size: 16px;line-height: 22px;">Other Charges </p></td>
            <td width="20px">:</td>
            <td width="100px"><span style="margin:0;color:#52636B; font-style: normal;font-size: 15px;line-height: 22px;">₹ <?php echo (int)($total_price - $commission_price); ?></span></td>
        </tr>
        <tr>
            <td width="200px"><p style="margin:0;color:#37474F; font-style: normal;font-size: 16px;line-height: 22px;">Total Amount </p></td>
            <td width="20px">:</td>
            <td width="100px"><span style="margin:0;color:#52636B; font-style: normal;font-size: 15px;line-height: 22px;">₹ <?php echo $total_price; ?></span></td>
        </tr>
    </table>
    
</div>
<div style="position:absolute; width:97.5%; top:95%;padding:8px; text-align:center; background-color:#F2F2F2; color:#37474F;font-family: 'Simonetta', cursive;">
    Thank You! See You Again
</div>
</body>