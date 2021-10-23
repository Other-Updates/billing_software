<?php $theme_path = $this->config->item('theme_locations').$this->config->item('active_template');  ?> 
<link href="<?=$theme_path?>/css/print_page.css" rel="stylesheet">
<script type="text/javascript" src="<?=$theme_path?>/js/attendance.js"></script>
<style type="text/css">
@media print
{
@page{ size : portrait;}
.disp_attr
{
display:inline !important;
}
.emp_title  {

color:#000000;
}


}
table tr td.lop_class, .lop_class{ width:1%;}
.emp_info {
text-align:center;
}

.emp_info span + span{
font-weight: bold;

padding-left:12%;



}


.disp_attr
{
display:none;
}
</style>
 <div class="contentinner">
    <h4 class="widgettitle">Attendance -
    <?php 
	$this->load->model('masters/shift_model');
	$this->load->model('masters/user_shift_model');
	$month_val = array("January","February","March","April","May","June","July","August","September","October","November","December");
	if(isset($year))
	{
		echo $month_val[$month-1]." ".$year;
		if($year == date('Y'))
		{
			if($month == date('m'))
				$days = date('d');
			else
				$days = cal_days_in_month(CAL_GREGORIAN, $month, $year);
		
		}
		else
			$days = cal_days_in_month(CAL_GREGORIAN, $month, $year);
		
	}
	else	
	{	
		echo date('F')." ".date('Y');
		
		$days = date('d');
		
		$month = date('m');
		
		$year = date('Y');
		
		
	}?>
    </h4>           
    <div class="widgetcontent">
    	<?php
		 	$attributes = array('class' => 'stdform editprofileform','method'=>'post');

                echo form_open('',$attributes);
				$user_role = json_decode($roles[0]["roles"]);
				?>	
                <?php 
					$days_array = array();
			$satur_holiday = 0;
			if(isset($saturday_holiday))
			{
				if($saturday_holiday==1)
					$satur_holiday = 1;
			}
			if($user_salary[0]["type"]=="monthly")
			{
				$starting_date= $start_date;
				$ending_date =$end_date;
			}
			else
			{
				$starting_date = $week_start_date;
				$ending_date =$week_end_date;
			}
				$s_date = date('d-m-Y',strtotime($starting_date));
				$std_dt = $ending_date." 00:00:00";
				$exclude_date = new DateTime($std_dt.' +1 day');
				$e_date = $exclude_date->format('d-m-Y');
				
				$start = new DateTime($s_date.' 00:00:00');
				//Create a DateTime representation of the last day of the current month based off of "now"
				$end = new DateTime( $e_date.' 00:00:00');
				//Define our interval (1 Day)
				$interval = new DateInterval('P1D');
				//Setup a DatePeriod instance to iterate between the start and end date by the interval
				$period = new DatePeriod( $start, $interval, $end );
				
				//Iterate over the DatePeriod instance
				$sunday = array();
				foreach( $period as $date ){
					//Make sure the day displayed is ONLY sunday.
					$days_array[] = $date->format( 'd-m-Y' );
				}
		
					$attendance = array();
					$editform = "";
					if(isset($attendance_month) && !empty($attendance_month))
					{
						foreach($attendance_month as $am)
						{
							$attendance[$am["attendance_date"]] = $am;
						
						
						}
					}
				$leave_arr = array();
				
			if(isset($leave) && !empty($leave))
			{
				foreach($leave as $lval)
				{
					$current_shift_id = $this->user_shift_model->get_user_current_shift_by_user_id($user_id,$lval["l_from"]);
							
					$current_shift = $this->shift_model->get_shift_regular_time_by_shift_id($current_shift_id[0]["shift_id"]);
					$start_hour = strtotime($current_shift[0]["from_time"]);
					$end_hour = strtotime($current_shift[0]["to_time"]);
							
					if($lval["l_from"] == $lval["l_to"])
					{
						if($start_hour>=$end_hour)
						{
							
							$sdt = date("H:i",strtotime($lval["leave_from"]));
							$edt = date("H:i",strtotime($lval["leave_to"]));
							
							if(strtotime("00:00")<strtotime($sdt) && (strtotime($edt)<$end_hour))
							{
								$previous =  date('Y-m-d', strtotime($lval["l_from"] .' -1 day'));
							/*	$date_dt = date('d-m-Y',strtotime($lval["l_from"]));
							$date_dt2 = new DateTime($std_dt.' -1 day');*/
							//print_r($date_dt2);
							//$date_dt3 = $exclude_date->format('d-m-Y');
							 	$leave_arr[date('d-m-Y',strtotime($previous))] = $lval;
							}
							else
								$leave_arr[$lval["l_from"]] = $lval;
						}
						else
							$leave_arr[$lval["l_from"]] = $lval;
					}
					else if($lval["type"]=="permission")
					{
						//$leave_arr[$lval["l_from"]] = $lval;
						if($start_hour>=$end_hour)
						{
							
							$sdt = date("H:i",strtotime($lval["leave_from"]));
							$edt = date("H:i",strtotime($lval["leave_to"]));
							
							if(strtotime("00:00")<strtotime($sdt) && (strtotime($edt)<$end_hour))
							{
								$previous =  date('Y-m-d', strtotime($lval["l_from"] .' -1 day'));
							/*	$date_dt = date('d-m-Y',strtotime($lval["l_from"]));
							$date_dt2 = new DateTime($std_dt.' -1 day');*/
							//print_r($date_dt2);
							//$date_dt3 = $exclude_date->format('d-m-Y');
							 	$leave_arr[date('d-m-Y',strtotime($previous))] = $lval;
							}
							else
								$leave_arr[$lval["l_from"]] = $lval;
						}
						else
							$leave_arr[$lval["l_from"]] = $lval;
					}
					else
					{
						
						$start = $lval["l_from"];
						$std_dt = date('Y-m-d',strtotime($lval["l_to"]));
						$end_current = new DateTime($lval["l_to"].' 00:00:00');
						$exclude_date = new DateTime($std_dt.' +1 day');
						$end = $exclude_date->format('d-m-Y');
						$start = new DateTime($start.' 00:00:00');
						//Create a DateTime representation of the last day of the current month based off of "now"
						$end = new DateTime( $end.' 00:00:00');
						$interval_od = dateTimeDiff($start,$end_current);
						if($lval["type"]=="on-duty")
						{
							
							if($start_hour>=$end_hour)
							{
								//Define our interval (1 Day)
								
								$interval = new DateInterval('P1D');
								//Setup a DatePeriod instance to iterate between the start and end date by the interval
								$period = new DatePeriod( $start, $interval, $end_current );
								
								//Iterate over the DatePeriod instance
								$lval["shift"]="night";
								$sunday = array();
								foreach( $period as $date ){
									//Make sure the day displayed is ONLY sunday.
									
									$leave_arr[$date->format( 'd-m-Y' )] =$lval;
								}
							}
							else
							{
								
								$interval = new DateInterval('P1D');
								//Setup a DatePeriod instance to iterate between the start and end date by the interval
								$period = new DatePeriod( $start, $interval, $end );
								$lval["shift"]="day";
								//Iterate over the DatePeriod instance
								$sunday = array();
								foreach( $period as $date ){
									//Make sure the day displayed is ONLY sunday.
									
									$leave_arr[$date->format( 'd-m-Y' )] =$lval;
								}
							
							}
							
						}
						else
						{
							//Define our interval (1 Day)
							$interval = new DateInterval('P1D');
							//Setup a DatePeriod instance to iterate between the start and end date by the interval
							$period = new DatePeriod( $start, $interval, $end );
							
							//Iterate over the DatePeriod instance
							$sunday = array();
							foreach( $period as $date ){
								//Make sure the day displayed is ONLY sunday.
								
								$leave_arr[$date->format( 'd-m-Y' )] =$lval;
							}
						}
					}
				}
			}
		//$this->pre_print->view($leave_arr);
			
			$holi_arr = array();
			if(isset($holidays) && !empty($holidays))
			{
				foreach($holidays as $hval)
				{
					if($hval["holiday_from"] == $hval["holiday_to"])
						$holi_arr[$hval["h_from"]] = $hval;
					else
					{
						$start = $hval["h_from"];
						$std_dt = date('Y-m-d',strtotime($hval["h_to"]));
						$exclude_date = new DateTime($std_dt.' +1 day');
						$end = $exclude_date->format('d-m-Y');
						$start = new DateTime($std_dt.' 00:00:00');
						//Create a DateTime representation of the last day of the current month based off of "now"
						$end = new DateTime( $end.' 00:00:00');
						
						//Define our interval (1 Day)
						$interval = new DateInterval('P1D');
						//Setup a DatePeriod instance to iterate between the start and end date by the interval
						$period = new DatePeriod( $start, $interval, $end );
						
						//Iterate over the DatePeriod instance
						$sunday = array();
						foreach( $period as $date ){
							//Make sure the day displayed is ONLY sunday.
							$holi_arr[$date->format( 'd-m-Y' )] =$hval;
						}
					}
				}
			}
			//$this->pre_print->view($leave_arr);
		 	$joined_date = explode("-",$doj[0]["date"]);
					//echo $start_date;
					//echo $end_date;	
					//print_r($days_array);
					$user_days = array();
					if(isset($days_array) && !empty($days_array))
					{
						foreach($days_array as $d_val)
						{
							if(strtotime($d_val)>=strtotime($doj[0]["date"]) && strtotime($d_val)<=strtotime(date('d-m-Y')))
								$user_days[] = $d_val;
						}
					}
					//$this->pre_print->view($holi_arr);
				?>
                <h4 class="emp_info">
                	<span class=""><span class="emp_title">Employee Name</span> :<?php echo $name[0]['first_name']; ?>&nbsp;<?php echo $name[0]['last_name']; ?></span>
                	<span class=""><span class="emp_title">Department</span> : <?php echo $dept[0]['dep_name']; ?></span>
                    <span class=""><span class="emp_title">Designation</span> : <?php echo $dept[0]['des_name']; ?></span>
                 </h4>
                <table width="100%" border="0" class="">
                	
                <tbody>
                <tr class="no-display">
                  <td>
                        	<?php echo form_label('Select Year');?>
                            <span class="field">
                         
                            <?php $options=array(''=>'Select Year');
								
								
								$options=array(''=>'Select Year');
									$i = $joined_date[0];
								
								if($month==1)
								{
									if($joined_date[2]<$month_starting_date)
										$i = $joined_date[0]-1;	
								}
								for(;$i<=date('Y');$i++)
								{
									$options[$i] = $i;
								
								}
							
									$default = $year;
								
								echo form_dropdown('year',$options,$default,'id="year_select"');
							?>
                            
                          
                            </span>
                  </td>
                  <td>
                        <?php echo form_label('Select Month');?>
                            <span class="field">
                           <?php 
								//echo date('m');
							$month_arr = array("Jan","Feb","Mar","Apr","May","June","July","Aug","Sep","Oct","Nov","Dec");
							$options=array(''=>'Select Month');
								
								
									$default = $month;
									
									if($year == $joined_date[0] && $year== date('Y'))
									{
										if($joined_date[2]<$month_starting_date)
											$i=$joined_date[1]-2;
										else
											$i=$joined_date[1]-1;
										for(;$i<date('m');$i++)
										{
											if(isset($month_arr[$i]))
											$options[$i+1] = $month_arr[$i];
										
										}
									
									}
									else if($year == $joined_date[0])
									{
										if($joined_date[2]<$month_starting_date)
											 $i=$joined_date[1]-2;
										else
											$i=$joined_date[1]-1;
										for(;$i<12;$i++)
										{
											$options[$i+1] = $month_arr[$i];
										
										}
									}
									else if($year == date('Y'))
									{
										
										for($i=0;$i<date('m');$i++)
										{
											$options[$i+1] = $month_arr[$i];
										
										}
									}
									else
									{
										for($i=0;$i<12;$i++)
										{
											$options[$i+1] = $month_arr[$i];
										
										}
									
									}
								$default = $month;
								
								//print_r($default);
								//$options[$default] = $month_arr[$default-1];
								echo form_dropdown('month',$options,$default,'id="month_select"');
							?>
                            
                            </span>
                  </td>
                  <td width="3%">&nbsp;</td>
                  <td>
                  &nbsp;
                  <input type="submit" name="go" value="Go" id="go" class="btn btn-warning btn-rounded"></td>
                   <input type="hidden" name="start_date" id="start_date" value="<?=$start_date?>">
                  <input type="hidden" name="end_date" id="end_date" value="<?=$end_date?>">
                  <input type="hidden" name="month_start_date" id="month_start_date" value="<?=$month_start_date?>">
                  <input type="hidden" name="month_end_date" id="month_end_date" value="<?=$month_end_date?>">
                   <input type="hidden"  id="month_starting_date" value="<?=$month_starting_date?>">
                  <input type="hidden" id="week_starting_day" value="<?=$week_starting_day?>">
                  <input type="hidden" id="doj" value="<?=$doj[0]["date"]?>">
                </tr>
              </tbody></table>	

              <div class="scroll_bar"> 
              <table class="table table-bordered print_border print_td">
              <caption class="no-display">&nbsp;&nbsp;&nbsp;&nbsp;<span class="btn-rounded holiday_class">&nbsp;&nbsp;&nbsp;&nbsp;</span> - Holiday
                                <span class="btn-rounded fd_leave_class ">&nbsp;&nbsp;&nbsp;&nbsp;</span> - Full Day Leave&nbsp;&nbsp;&nbsp;&nbsp;
                                <span class="btn-rounded hd_leave_class ">&nbsp;&nbsp;&nbsp;&nbsp;</span> - Half Day Leave&nbsp;&nbsp;&nbsp;&nbsp;
                                <span class="btn-rounded permission_class ">&nbsp;&nbsp;&nbsp;&nbsp;</span> - Permission&nbsp;&nbsp;&nbsp;&nbsp;
                                <span class="btn-rounded compoff_class ">&nbsp;&nbsp;&nbsp;&nbsp;</span> - Compoff&nbsp;&nbsp;&nbsp;&nbsp;
                                <span class="btn-rounded lop_class ">&nbsp;&nbsp;&nbsp;&nbsp;</span> - LOP&nbsp;&nbsp;&nbsp;&nbsp;
                                <span class="btn-rounded on_duty_class ">&nbsp;&nbsp;&nbsp;&nbsp;</span> - On-duty&nbsp;&nbsp;&nbsp;&nbsp;
                                <span class="btn-rounded earned_leave_class ">&nbsp;&nbsp;&nbsp;&nbsp;</span> - Earned Leave&nbsp;&nbsp;&nbsp;&nbsp;
                                <span class="btn-rounded inactive_class ">&nbsp;&nbsp;&nbsp;&nbsp;</span> - Inactive status&nbsp;&nbsp;&nbsp;&nbsp;
                                </caption>
                                <span class="disp_attr" ><b>HD</b>- Half Day Leave</span>&nbsp;&nbsp;&nbsp;&nbsp;
                                <span class="disp_attr"><b>P</b>- Permission</span>
                          
            <thead>
            <tr>
             
             	<?php //print_r($user_days);
				//echo  $department[0]["ot_applicable"];
				
					$head = array("S.No","Date","Day","In Time - Out Time","Break / Lunch","Over Time","Total Hours");
				
				//print_r($head);
					foreach($head as $ele)
					{
						if($ele == "Over Time")
							echo "<th  class='ot_class'>".$ele."</th>";
						else
							echo "<th>".$ele."</th>";
					}
				?>
           	 <?php if($user_status[0]['status']==1)
			 {
			 if(in_array("attendance:edit_attendance",$user_role)){
			 ?>
           <th class="no-display">Action</th>
           <?php
		   	}
		    }?>
           
            <!--<th></th>-->
            </tr>
            </thead>
            <tbody>
            <?php 
				$ot_enable = 0;
				$this->load->model("attendance_model");	
					//print_r($dol);
					for($k=0;$k<=count($user_days)-1;$k++)
					{					
						$hour = 0;
						$mint = 0;
						$worked_hrs = 0;
						$time_exist = 0;
						$holiday= 0;
						$saturday = 0;
						$day_value = $user_days[$k];						
						if(isset($dol[0]["date"]) && strtotime($dol[0]["date"])<strtotime($day_value))
						{
						$date = date('l',strtotime($day_value));
						?>                        
						<tr class="inactive_class">
                        <td class="center"><?=$k+1?></td><td class="center"><?=$day_value?></td><td class="center"><?=$date?></td>
                        <td colspan="5" class="center">User in inactive status</td></tr>
						<?php 
						}
						else
						{
						$overtime_break = 0;
						$split_day = explode("-",$day_value);
						$current_day = ltrim($split_day[0],'0');
						$leave_type = '';
						$i = $half_day = $permission_hrs = 0;
						$style= "";
						$g = $k;
						$on_duty_text = "";
						if(isset($attendance[$day_value]['attendance_date'])):
							$break = $this->attendance_model->get_break_details_by_attendance_id($attendance[$day_value]["id"]);
							$i=1;
						endif;			
							
						$sun = date('l',strtotime($day_value));
						
						$overtimestart =0;
						$overtimeend=0;
						$regular_time = 0;
						$breaktimediff=0;
						$start_time = 0;
						$duty_hours = 0;
						$shift_end = 0;
						$on_duty = 0;
						$shift_start_time_without_threshold = 0;
						
						$current_shift_id = $this->user_shift_model->get_user_current_shift_by_user_id($user_id,$day_value);
							
						$current_shift = $this->shift_model->get_shift_details_by_shift_id($current_shift_id[0]["shift_id"]);
						if(isset($current_shift) && !empty($current_shift))
						{
							$shift = array();
							foreach ($current_shift as $key => $value) {
		
								$shift[$value["type"]][] = $value;
							}
						
						}
						$punch_in_time = date("d-m-Y H:i:s");
							$shift_start =0;
						if(isset($shift["regular"][0]))
						{
							
							$reg_st = explode(':',$shift["regular"][0]["from_time"]);
							$reg_et = explode(':',$shift["regular"][0]["to_time"]);
							$shift_in_time = $day_value." ".$shift["regular"][0]["from_time"];
							if($reg_st[0]>12 && $reg_et[0]<12)
							{
								$next_day = new DateTime(date('d-m-Y H:i:s').' + 1 day');
								$date8 = new DateTime(date('d-m-Y')." ".$shift["regular"][0]["from_time"]);
								$date9 = new DateTime($next_day->format('d-m-Y')." ".$shift["regular"][0]["to_time"]);
							}
							else
							{
								$date8 = new DateTime(date('d-m-Y')." ".$shift["regular"][0]["from_time"]);
								$date9 = new DateTime(date('d-m-Y')." ".$shift["regular"][0]["to_time"]);	
							}
							$start_time = $shift["regular"][0]["from_time"];
							$shift_end = $shift["regular"][0]["to_time"];
							$shift_start = date("d-m-Y H:i:s", strtotime($shift_in_time )-21600); 
							//print_r($date8);
							$regular_time = dateTimeDiff($date8,$date9);
						}
						if( strtotime($punch_in_time) <strtotime($shift_start))
							continue;
						if(isset($shift) && !empty($shift)):
						if(isset($shift["overtimestart"][0])) {
							$overtimestart = $shift["overtimestart"][0]["from_time"];
							$overtimeend = $shift["overtimestart"][0]["to_time"];
							$ot_enable = 1;
						}
							//echo $ot_enable;	
						
						//$this->pre_print->view($start_time);
						if(isset($shift["break"]))
						{
							foreach($shift["break"] as $sh)
							{
									$reg_st = explode(':',$sh["from_time"]);
									$reg_et = explode(':',$sh["to_time"]);
									if($reg_st[0]>12 && $reg_et[0]<12)
									{
										$next_day = new DateTime(date('d-m-Y H:i:s').' + 1 day');
										$date8 = new DateTime(date('d-m-Y')." ".$sh["from_time"]);
										$date9 = new DateTime($next_day->format('d-m-Y')." ".$sh["to_time"]);
									}
									else
									{	
										$date8 = new DateTime(date('d-m-Y')." ".$sh["from_time"]);
										$date9 = new DateTime(date('d-m-Y')." ".$sh["to_time"]);
									}	
									$inter = dateTimeDiff($date8,$date9);
									if($inter->h>0)
									{
										$breaktimediff = $breaktimediff + ($inter->h)*60;
									}
									if($inter->i>0)
									{
										$breaktimediff = $breaktimediff + ($inter->i);
									}
									if($inter->s>0)
									{
										$breaktimediff = $breaktimediff + ($inter->s/60);
									}
									
							}
						}	
						
						if(isset($shift["lunch"]))
						{
							foreach($shift["lunch"] as $sh)
							{
									$reg_st = explode(':',$sh["from_time"]);
									$reg_et = explode(':',$sh["to_time"]);
									if($reg_st[0]>12 && $reg_et[0]<12)
									{
										$next_day = new DateTime(date('d-m-Y H:i:s').' + 1 day');
										$date10 = new DateTime(date('d-m-Y')." ".$sh["from_time"]);
										$date11 = new DateTime($next_day->format('d-m-Y')." ".$sh["to_time"]);
									}
									else
									{	
										$date10 = new DateTime(date('d-m-Y')." ".$sh["from_time"]);
										$date11 = new DateTime(date('d-m-Y')." ".$sh["to_time"]);
									}	
									$inter = dateTimeDiff($date10,$date11);
									//print_r($inter);
									if($inter->h>0)
									{
										$breaktimediff = $breaktimediff + ($inter->h)*60;
									}
									if($inter->i>0)
									{
										$breaktimediff = $breaktimediff + ($inter->i);
									}
									if($inter->s>0)
									{
										$breaktimediff = $breaktimediff + ($inter->s/60);
									}
									
							}
						}	
							$regular_time_val =($regular_time->h * 60)+$regular_time->i -  $breaktimediff - $threshold[0]['value'] ;
							if($regular_time->s>0)
							{
								$regular_time_val = $regular_time_val + ($regular_time->s/60);
							}
							//echo $regular_time_val ;
							//print_r($regular_time);
						endif;
				//endif;
					$res = explode(':',$start_time);
					if(!isset($res[1]))
						$res[1]=0;
						$shift_start_time_without_threshold = $res[0]*60+$res[1];
						if(isset($res[2]) && $res[2]>0)
						{
							$shift_start_time_without_threshold = $shift_start_time_without_threshold + ($res[2]/60);
						}
						$shift_start_time = $res[0]*60+$res[1]+$threshold[0]['value'];
						if(isset($res[2]) && $res[2]>0)
						{
							$shift_start_time = $shift_start_time + ($res[2]/60);
						}
						if($sun == "Sunday")
						{
							$style_class = $style = "holiday_class";
						}
						if($satur_holiday ==1)
						{
							if($sun =="Saturday")
							{
								$style_class = $style = "holiday_class";
								$saturday = 1;
							}
						}
						if(isset($holi_arr[$day_value]) && !empty($holi_arr[$day_value]))
						{
							$holiday=1;
							
							$style = "holiday_class";
							
							
						}
						
						$leave_type = '';
						
							if(isset($leave_arr[$day_value])&& !empty($leave_arr[$day_value]))
							{
								
								$date1 = new DateTime(date('d-m-Y H:i:s',strtotime($leave_arr[$day_value]["leave_from"])));
								$date2 = new DateTime(date('d-m-Y H:i:s',strtotime($leave_arr[$day_value]["leave_to"])));
								$interval =dateTimeDiff($date1,$date2);
								if($leave_arr[$day_value]['type']=="sick leave" ||  $leave_arr[$day_value]['type']=="casual leave" ||  $leave_arr[$day_value]['type']=="earned leave")
								{
								
									if($date1==$date2)
									{
										$leave_type = 2;
									
									}
									else
									{
										if($interval->d==0)
										{
											$leave_type=1;
											$half_day = ($interval->h*60)+$interval->i;
										}
										else
										{
											$leave_type=2;
											
										}
									}
								}
								else if($leave_arr[$day_value]['type']=="permission" )
								{
									$leave_type=3;
									$permission_hrs = ($interval->h*60)+$interval->i;
									
								}
								else if($leave_arr[$day_value]['type']=="compoff" )
								{
									$leave_type=4;
								}
								else if($leave_arr[$day_value]['type']=="on-duty" )
								{
								
									$leave_type=5;
								}
							}
							
							if($leave_type==4 || $leave_type==2)
							{
								
								//echo $day_value;if($leave_arr[$day_value]['lop']==1)
								if(isset($attendance[$day_value]) && !empty($attendance[$day_value]) && $attendance[$day_value]["in"]!=NULL && $attendance[$day_value]["in"]!="" && $attendance[$day_value]["in"]!="00:00:00")
								{
									goto show_attendance;
								}
								else
								{
									if($leave_arr[$day_value]['lop']==1)
									{
										$style ="lop_class";
										
									}
									else
									{   
									    if($leave_arr[$day_value]['type']=="earned leave")
											$style ="earned_leave_class";
										else
											$style ="fd_leave_class";
										if($leave_type == 4)
											$style="compoff_class";
									}
									
									if($user_status[0]['status']==1)
									{
										echo "<tr><td class='center ".$style."' >".($k+1)."</td>
											<td class='center ".$style."' >".$day_value."</td>
											<td  class='center ".$style."'>".$sun."</td>";
										echo "<td colspan='5' class='colspan_td center ".$style."' >".$leave_arr[$day_value]["reason"]."</td>";
									}
									else
									{
										echo "<tr><td class='center ".$style."' >".($k+1)."</td>
											<td class='center ".$style."' >".$day_value."</td>
											<td  class='center ".$style."'>".$sun."</td>";
										echo "<td colspan='4' class='center colspan_td ".$style."' >".$leave_arr[$day_value]["reason"]."</td>";
									}
								
								}
							}
							else
							{
								
									//echo $shift_start_time_without_threshold;
									
									if($leave_type==1)
									{
										if(isset($leave_arr[$day_value]))
										{
											if($leave_arr[$day_value]["lop"]==0)
												$style = "hd_leave_class";
											else
												$style = "lop_class";
										}
												
									}
									else if($leave_type==3)
									{
										if(isset($leave_arr[$day_value]))
										{
											if($leave_arr[$day_value]["lop"]==0)
												$style = "permission_class";
											else
												$style = "lop_class";
										}
								
									}
									else if($leave_type==5)
									{
										$style = "on_duty_class";
										$od_dt = $day_value." 00:00:00";
										$exclude_date = new DateTime($od_dt.' +1 day');
										$next_day = $exclude_date->format('d-m-Y');
										if($leave_arr[$day_value]["l_from"]==$day_value && $leave_arr[$day_value]["l_to"]==$day_value)
											{
												$ds = explode(':',date('H:i:s',strtotime($leave_arr[$day_value]["leave_from"])));
												$duty_start_time = $ds[0]*60+$ds[1];
												if(isset($ds[2]) && $ds[2]>0)
												{
													$duty_start_time = $duty_start_time + ($ds[2]/60);
													
												}
												$ds_to = explode(':',date('H:i:s',strtotime($leave_arr[$day_value]["leave_to"])));
												$duty_end_time = $ds_to[0]*60+$ds_to[1];
												if(isset($ds_to[2]) && $ds_to[2]>0)
												{
													$duty_end_time = $duty_end_time + ($ds_to[2]/60);
													
												}
												$d_from = new DateTime($day_value." ".date('H:i:s',strtotime($leave_arr[$day_value]["leave_from"])));
												$d_to = new DateTime($day_value." ".date('H:i:s',strtotime($leave_arr[$day_value]["leave_to"])));
												$d_inter = dateTimeDiff($d_from,$d_to);
												$duty_hours = $d_inter->h*60 + $d_inter->i + $d_inter->i/60;
												//echo $duty_hours;
												$duty_hours = $regular_time_val-$duty_hours;
												if($duty_hours>=$regular_time_val)
												{
													if($user_status[0]['status']==1)
													{
														echo "<tr><td class='center ".$style."' >".($k+1)."</td>
															<td class='center ".$style."' >".$day_value."</td>
															<td  class='center ".$style."'>".$sun."</td>";
														echo "<td colspan='5' class='colspan_td center ".$style."' >".$leave_arr[$day_value]["reason"]."</td>";
													}
													else
													{
														echo "<tr><td class='center ".$style."' >".($k+1)."</td>
															<td class='center ".$style."' >".$day_value."</td>
															<td  class='center ".$style."'>".$sun."</td>";
														echo "<td colspan='4' class='center colspan_td ".$style."' >".$leave_arr[$day_value]["reason"]."</td>";
													}
													continue;
												}
												else
												{
													if($shift_start_time>$duty_start_time)
														$on_duty = 1;
														$on_duty_text = date('H:i',strtotime($leave_arr[$day_value]['leave_from']))." - ".date('H:i',strtotime($leave_arr[$day_value]['leave_to']));
												}
												goto show_attendance;
												
											
											}
										elseif($leave_arr[$day_value]["l_from"]==$next_day && $leave_arr[$day_value]["l_to"]==$next_day)
											{
												$ds = explode(':',date('H:i:s',strtotime($leave_arr[$day_value]["leave_from"])));
												$duty_start_time = $ds[0]*60+$ds[1];
												if(isset($ds[2]) && $ds[2]>0)
												{
													$duty_start_time = $duty_start_time + ($ds[2]/60);
													
												}
												$ds_to = explode(':',date('H:i:s',strtotime($leave_arr[$day_value]["leave_to"])));
												$duty_end_time = $ds_to[0]*60+$ds_to[1];
												if(isset($ds_to[2]) && $ds_to[2]>0)
												{
													$duty_end_time = $duty_end_time + ($ds_to[2]/60);
													
												}
												$d_from = new DateTime($day_value." ".date('H:i:s',strtotime($leave_arr[$day_value]["leave_from"])));
												$d_to = new DateTime($day_value." ".date('H:i:s',strtotime($leave_arr[$day_value]["leave_to"])));
												$d_inter = dateTimeDiff($d_from,$d_to);
												$duty_hours = $d_inter->h*60 + $d_inter->i + $d_inter->i/60;
												//echo $duty_hours;
												$duty_hours = $regular_time_val-$duty_hours;
												if($duty_hours>=$regular_time_val)
												{
													if($user_status[0]['status']==1)
													{
														echo "<tr><td class='center ".$style."' >".($k+1)."</td>
															<td class='center ".$style."' >".$day_value."</td>
															<td  class='center ".$style."'>".$sun."</td>";
														echo "<td colspan='5' class='colspan_td center ".$style."' >".$leave_arr[$day_value]["reason"]."</td>";
													}
													else
													{
														echo "<tr><td class='center ".$style."' >".($k+1)."</td>
															<td class='center ".$style."' >".$day_value."</td>
															<td  class='center ".$style."'>".$sun."</td>";
														echo "<td colspan='4' class='center colspan_td ".$style."' >".$leave_arr[$day_value]["reason"]."</td>";
													}
													continue;
												}
												else
												{
													if($shift_start_time>$duty_start_time)
														$on_duty = 1;
														$on_duty_text = date('H:i',strtotime($leave_arr[$day_value]['leave_from']))." - ".date('H:i',strtotime($leave_arr[$day_value]['leave_to']));
												}
												goto show_attendance;
												
											
											}	
										elseif($leave_arr[$day_value]["shift"]=="day")
										{
											if($leave_arr[$day_value]["l_from"]==$day_value && $leave_arr[$day_value]["l_to"]!=$day_value)
											{
												//echo "enter";
												$ds = explode(':',date('H:i:s',strtotime($leave_arr[$day_value]["leave_from"])));
												$duty_start_time = $ds[0]*60+$ds[1];
												if(isset($ds[2]) && $ds[2]>0)
												{
													$duty_start_time = $duty_start_time + ($ds[2]/60);
													
												}
												if($shift_start_time<$duty_start_time)
												{
													
													$d_from = new DateTime($day_value." ".$start_time);
													$d_to = new DateTime($day_value." ".date('H:i:s',strtotime($leave_arr[$day_value]["leave_from"])));
													$d_inter = dateTimeDiff($d_from,$d_to);
													$duty_hours = $d_inter->h*60 + $d_inter->i + $d_inter->i/60;
												//	print_r($d_inter);
												$on_duty_text = date('H:i',strtotime($leave_arr[$day_value]['leave_from']))." - ".$shift_end ;
													goto show_attendance;
												}
												else if($shift_start_time>=$duty_start_time)
												{
													if($user_status[0]['status']==1)
													{
														echo "<tr><td class='center ".$style."' >".($k+1)."</td>
															<td class='center ".$style."' >".$day_value."</td>
															<td  class='center ".$style."'>".$sun."</td>";
														echo "<td colspan='5' class='colspan_td center ".$style."' >".$leave_arr[$day_value]["reason"]."</td>";
													}
													else
													{
														echo "<tr><td class='center ".$style."' >".($k+1)."</td>
															<td class='center ".$style."' >".$day_value."</td>
															<td  class='center ".$style."'>".$sun."</td>";
														echo "<td colspan='4' class='center colspan_td ".$style."' >".$leave_arr[$day_value]["reason"]."</td>";
													}
													continue;
												
												}
											}
											else if($leave_arr[$day_value]["l_from"]!=$day_value && $leave_arr[$day_value]["l_to"]!=$day_value)
											{
												if($user_status[0]['status']==1)
												{
													echo "<tr><td class='center ".$style."' >".($k+1)."</td>
														<td class='center ".$style."' >".$day_value."</td>
														<td  class='center ".$style."'>".$sun."</td>";
													echo "<td colspan='5' class='colspan_td center ".$style."' >".$leave_arr[$day_value]["reason"]."</td>";
												}
												else
												{
													echo "<tr><td class='center ".$style."' >".($k+1)."</td>
														<td class='center ".$style."' >".$day_value."</td>
														<td  class='center ".$style."'>".$sun."</td>";
													echo "<td colspan='4' class='center colspan_td ".$style."' >".$leave_arr[$day_value]["reason"]."</td>";
												}
													continue;
											
											}
											elseif($leave_arr[$day_value]["l_from"]!=$day_value && $leave_arr[$day_value]["l_to"]==$day_value)
											{
												$ds = explode(':',date('H:i:s',strtotime($leave_arr[$day_value]["leave_to"])));
												$duty_end_time = $ds[0]*60+$ds[1];
												if(isset($ds[2]) && $ds[2]>0)
												{
													$duty_end_time = $duty_end_time + ($ds[2]/60);
													
												}
												if($shift_start_time<$duty_end_time)
												{
													
													
													$d_from = new DateTime($day_value." ".date('H:i:s',strtotime($leave_arr[$day_value]["leave_to"])));
													$d_to = new DateTime($day_value." ".$shift_end);
													$d_inter = dateTimeDiff($d_from,$d_to);
													$duty_hours = $d_inter->h*60 + $d_inter->i + $d_inter->i/60;
													$on_duty = 1;
													$on_duty_text =$start_time." - ". date('H:i',strtotime($leave_arr[$day_value]['leave_to']));
													//print_r($d_inter);
													goto show_attendance;
												}
												else if($shift_start_time>=$duty_end_time)
												{
													$duty_hours = 0;
													goto show_attendance;
												
												}
											}
										}
										elseif($leave_arr[$day_value]["shift"]=="night")
										{
											
											if($leave_arr[$day_value]["l_from"]==$day_value && $leave_arr[$day_value]["l_to"]==$next_day)
											{
												$ds = explode(':',date('H:i:s',strtotime($leave_arr[$day_value]["leave_from"])));
												$duty_start_time = $ds[0]*60+$ds[1];
												if(isset($ds[2]) && $ds[2]>0)
												{
													$duty_start_time = $duty_start_time + ($ds[2]/60);
													
												}
												$ds_to = explode(':',date('H:i:s',strtotime($leave_arr[$day_value]["leave_to"])));
												$duty_end_time = $ds_to[0]*60+$ds_to[1];
												if(isset($ds_to[2]) && $ds_to[2]>0)
												{
													$duty_end_time = $duty_end_time + ($ds_to[2]/60);
													
												}
												$d_from = new DateTime($day_value." ".date('H:i:s',strtotime($leave_arr[$day_value]["leave_from"])));
												$d_to = new DateTime($next_day." ".date('H:i:s',strtotime($leave_arr[$day_value]["leave_to"])));
												$d_inter = dateTimeDiff($d_from,$d_to);
												$duty_hours = $d_inter->h*60 + $d_inter->i + $d_inter->i/60;
												//echo $duty_hours;
												$duty_hours = $regular_time_val-$duty_hours;
												if($duty_hours>=$regular_time_val)
												{
													if($user_status[0]['status']==1)
													{
														echo "<tr><td class='center ".$style."' >".($k+1)."</td>
															<td class='center ".$style."' >".$day_value."</td>
															<td  class='center ".$style."'>".$sun."</td>";
														echo "<td colspan='5' class='colspan_td center ".$style."' >".$leave_arr[$day_value]["reason"]."</td>";
													}
													else
													{
														echo "<tr><td class='center ".$style."' >".($k+1)."</td>
															<td class='center ".$style."' >".$day_value."</td>
															<td  class='center ".$style."'>".$sun."</td>";
														echo "<td colspan='4' class='center colspan_td ".$style."' >".$leave_arr[$day_value]["reason"]."</td>";
													}
													continue;
												}
												else
												{
													if($shift_start_time>$duty_start_time)
														$on_duty = 1;
														$on_duty_text = date('H:i',strtotime($leave_arr[$day_value]['leave_from']))." - ".date('H:i',strtotime($leave_arr[$day_value]['leave_to']));
												}
												goto show_attendance;
												
											}
											else if($leave_arr[$day_value]["l_from"]==$day_value && $leave_arr[$day_value]["l_to"]!=$day_value)
											{
												$ds = explode(':',date('H:i:s',strtotime($leave_arr[$day_value]["leave_from"])));
												$duty_start_time = $ds[0]*60+$ds[1];
												if(isset($ds[2]) && $ds[2]>0)
												{
													$duty_start_time = $duty_start_time + ($ds[2]/60);
													
												}												
												if($shift_start_time<$duty_start_time)
												{
													
													$d_from = new DateTime($day_value." ".$start_time);
													$d_end = date('H:i:s',strtotime($leave_arr[$day_value]["leave_from"]));
													if(strtotime($d_end)<=strtotime($start_time))
														$d_to = new DateTime($day_value." ".$d_end);
													else
														$d_to = new DateTime($next_day." ".$d_end);
													$d_inter = dateTimeDiff($d_from,$d_to);
													$duty_hours = $d_inter->h*60 + $d_inter->i + $d_inter->i/60;												
												$on_duty_text = date('H:i',strtotime($leave_arr[$day_value]['leave_from']))." - ".$shift_end ;
													
												}
												else if($shift_start_time>=$duty_start_time)
												{
													echo "<tr><td class='center' style='".$style_class."' >".($k+1)."</td>
													<td class='center' style='".$style_class."' >".$day_value."</td>
													<td class='center'  style='".$style_class."'>".$sun."</td>
													<td colspan='6' style='".$style_class."' class='center colspan_td'>".$leave_arr[$day_value]["reason"]."</td>";
													continue;
												
												}
											}
											else if($leave_arr[$day_value]["l_from"]!=$day_value && $leave_arr[$day_value]["l_to"]!=$day_value)
											{
												if($next_day == $leave_arr[$day_value]["l_to"])
												{
													
													$ds = explode(':',date('H:i:s',strtotime($leave_arr[$day_value]["leave_to"])));
													$duty_end_time = $ds[0]*60+$ds[1];
													if(isset($ds[2]) && $ds[2]>0)
													{
														$duty_end_time = $duty_end_time + ($ds[2]/60);
														
													}												
													if($shift_start_time>=$duty_end_time)
													{
														
														$d_st =date('H:i:s',strtotime($leave_arr[$day_value]["leave_to"]));
														$d_from = new DateTime($day_value." ".$d_st);
														if(strtotime($d_st)>=strtotime($shift_end))
															$d_to = new DateTime($next_day." ".$shift_end);
														else
														
															$d_to = new DateTime($day_value." ".$shift_end);
														$d_inter = dateTimeDiff($d_from,$d_to);
														$duty_hours = $d_inter->h*60 + $d_inter->i + $d_inter->i/60;
														$on_duty = 1;
														$on_duty_text =$start_time." - ". date('H:i',strtotime($leave_arr[$day_value]['leave_to']));													
														goto show_attendance;
													}
													else if($shift_start_time<$duty_end_time)
													{
														$duty_hours = 0;
														goto show_attendance;
													
													}
												}
												else
												{
													if($user_status[0]['status']==1)
													{
														echo "<tr><td class='center ".$style."' >".($k+1)."</td>
															<td class='center ".$style."' >".$day_value."</td>
															<td  class='center ".$style."'>".$sun."</td>";
														echo "<td colspan='5' class='colspan_td center ".$style."' >".$leave_arr[$day_value]["reason"]."</td>";
													}
													else
													{
														echo "<tr><td class='center ".$style."' >".($k+1)."</td>
															<td class='center ".$style."' >".$day_value."</td>
															<td  class='center ".$style."'>".$sun."</td>";
														echo "<td colspan='4' class='center colspan_td ".$style."' >".$leave_arr[$day_value]["reason"]."</td>";
													}
													continue;
												}
											
											}
											
										}
									}								
							
							show_attendance:
								if(isset($attendance[$day_value]["in"]) && $attendance[$day_value]["in"]!="")
								{
									
									$att_time = explode(':',$attendance[$day_value]["in"]);
									$att_calc = $att_time[0]*60 + $att_time[1];									
									if(isset($att_time[2]) && $att_time[2]>0)
											$att_calc = $att_calc +($att_time[2]/60);
													
									if($att_calc>$shift_start_time)
									{										
										if(date('l',strtotime($day_value))!="Sunday" && $holiday==0 && $saturday!=1 && $on_duty!=1)
											$style = "lop_class";
									}
								}						
								
								if($i==1)
								{									
									if($attendance[$day_value]["in"]!="" && $attendance[$day_value]["in"]!="00:00:00" && $attendance[$day_value]["out"]!="" && $attendance[$day_value]["out"]!="00:00:00")
									{
										$time_exist = 1;
										
										$first=$attendance[$day_value]["attendance_date"]." ".$attendance[$day_value]["in"];
										$sec=$attendance[$day_value]["attendance_date"]." ".$attendance[$day_value]["out"];
										if(strtotime($sec)<=strtotime($first))
										{
											$exclude_date = new DateTime($sec.' +1 day');
											$sec=$exclude_date->format('d-m-Y')." ".$attendance[$day_value]["out"];
										}
										$date1 = new DateTime($first);
										$date2 = new DateTime($sec);
										$interval = dateTimeDiff($date1,$date2);
										$total = 0;
										if(isset($break) && !empty($break))
										{
											
											foreach($break as $br)
											{
												
												if($br["in_time"]!="00:00:00" && $br["in_time"]!=NULL)
												{
													$reg_st = explode(':',$br["in_time"]);
													$reg_et = explode(':',$br["out_time"]);
													if($reg_st[0]>12 && $reg_et[0]<12)
													{
														$next_day = new DateTime(date('d-m-Y H:i:s').' + 1 day');
														$date4 = new DateTime(date('d-m-Y')." ".$br["in_time"]);
														$date5 = new DateTime($next_day->format('d-m-Y')." ".$br["out_time"]);
													}
													else
													{	
														$date4 = new DateTime(date('d-m-Y')." ".$br["in_time"]);
														$date5 = new DateTime(date('d-m-Y')." ".$br["out_time"]);
													}														
													$interval2 = dateTimeDiff($date4,$date5);
													
													
													if($ot_enable == 1)
													{
														$ot_br_start =  strtotime($date4->format('Y-m-d H:i:s'));
														$ot_br_end = strtotime($date5->format('Y-m-d H:i:s'));
														$ot_start1=date('d-m-Y')." ".$overtimestart;
														$ot_end1 =date('d-m-Y')." ".$overtimeend;
														if(strtotime($ot_end1)<=strtotime($ot_start1))
														{
															$exclude_date = new DateTime($ot_end1.' +1 day');
															$ot_end1 =$exclude_date->format('d-m-Y')." ".$overtimeend;
														}														
														
														if($ot_br_start>=strtotime($ot_start1))
														{
															if($ot_br_end<=strtotime($ot_end1))
															$overtime_break =	$overtime_break +  $interval2->h *60 +$interval2->i+ ($interval2->s/60);	
															else
															{
																
																$date12 = new DateTime($ot_end1);
																$ot_br_interval = dateTimeDiff($date4,$date12);
																$overtime_break =	$overtime_break +  $ot_br_interval->h *60 +$ot_br_interval->i+ ($ot_br_interval->s/60);	
															}	
														}
														
													}
													$total = $total+$interval2->i;
													if($interval2->h>0)
														$total = $total + $interval2->h *60;
													if($interval2->s>0)
														$total = $total + ($interval2->s/60);
												}
											}
									
										}
										
										$whole = ($interval->h)*60 + $interval->i-$total;
										if($interval->s>0)
													$whole = $whole + ($interval->s/60);										
										$hour = floor($whole/60);
										$mint = $whole%60;
										$worked_hrs = ($interval->h)*60 + $interval->i-$total;
										if($interval->s>0)
												$worked_hrs = $worked_hrs + ($interval->s/60);										
										if($permission_hrs!=0)
										{	
											if($regular_time_val - $worked_hrs -$permission_hrs > 0)
											{
												
												 if(date('l',strtotime($day_value))!="Sunday" && $holiday!=1 && $saturday!=1 )
												 	$style = "lop_class";
											}
										}
										else if($duty_hours!=0)
										{	
										
											if($worked_hrs <$duty_hours)
											{												
												 if(date('l',strtotime($day_value))!="Sunday" && $holiday!=1 && $saturday!=1)
												 	$style = "lop_class";
											}
										}
										else if($half_day!=0)
										{
											
											if($regular_time_val - $worked_hrs -$half_day > 0)
											{
												 if(date('l',strtotime($day_value))!="Sunday" && $holiday!=1 && $saturday!=1)
													$style = "lop_class";
											}
										}
										else
										{
											
											if($regular_time_val - $worked_hrs  > 0)
											{								
												 if(date('l',strtotime($day_value))!="Sunday" && $holiday!=1 && $saturday!=1)
												 {
												 
													$style = "lop_class";
												}
												
											}
											
										}
						
								}
								
							}
							else
							{
								if(date('l',strtotime($day_value))!="Sunday" && $holiday!=1 && $saturday!=1)
											$style = "lop_class";
							}			
					?>				
                	<tr>
                	<td class="center <?=$style?>"><?=$k+1?></td>
                   
					<td class="center <?=$style?>">
					 <?php
					 		//echo $g;
							if(isset($attendance[$day_value]['attendance_date'])){
							//echo $attendance_month[$day_value-1]['attendance_date'];
							 if($day_value==$attendance[$day_value]['attendance_date']){
								echo $attendance[$day_value]['attendance_date'];
								$i=1;
								}
							}
							else
							{
								
								echo $day_value;
							}
								?>                           
					</td>
                     <td class="center <?=$style?>"><?=$sun?></td>
                     <td   class="center <?=$style?>">
                    	<?php 
							echo "<input type='hidden' value='".$day_value."' name='day_value[]' class='day_value' disabled='disabled' >";
							if($i==1){
								if($attendance[$day_value]["in"]!="00:00:00") echo "<span class='time_calc'>".$attendance[$day_value]["in"]." - ".$attendance[$day_value]["out"]."</span>";
								else
								 echo "<span class='time_calc'></span>";
								$intime =array("name"=>"in_time[".$day_value."]","value"=>$attendance[$day_value]["in"],"class"=>'time_in input-small',"disabled"=>"disabled");
								echo form_input($intime);
								$outtime = array("name"=>"out_time[".$day_value."]","value"=>$attendance[$day_value]["out"],"class"=>"time_out input-small","disabled"=>"disabled");
								echo form_input($outtime);
						}
						else
						{
								 echo "<span class='time_calc'></span>";
								$intime =array("name"=>"in_time[".$day_value."]","value"=>"00:00:00","class"=>'time_in input-small',"disabled"=>"disabled" );
								echo form_input($intime);
								$outtime = array("name"=>"out_time[".$day_value."]","value"=>"00:00:00","class"=>"time_out input-small","disabled"=>"disabled");
								echo form_input($outtime);
						
						
						}
						
						?>
                    	 <input type="hidden" value="<?=$shift["regular"][0]["from_time"]?>" class="shift_in_time" />
                    	<input type="hidden" value="<?=$shift["regular"][0]["to_time"]?>" class="shift_out_time" />
                    </td>
                    <td  class="center break_td <?=$style?>">
                    	<?php 
							$enter='';
							if($i==1){
							
							//print_r($interval);
							if(isset($break) && !empty($break))
							{
								$j= 0;
								$enter=0;
								foreach($break as $br)
								{
									
									if($br["in_time"]!="00:00:00")
									{
										$enter=1;
										echo "<span class='break_time'>".$br["in_time"]." - ".$br["out_time"]."</span><span class='break_to_clone'>";
										$br_in =array("name"=>"break[in_time][".$day_value."][]","value"=>$br["in_time"],"class"=>'in_break input-small break',"disabled"=>"disabled");
										echo form_input($br_in);
										$br_out = array("name"=>"break[out_time][".$day_value."][]","value"=>$br["out_time"],"class"=>"out_break input-small","disabled"=>"disabled");
										echo form_input($br_out);
										echo "</span>";
										if($j==0)
										{
											echo  '&nbsp;<a href="javascript:void(0);" title="Add" class="btn btn-danger add_row"><i class="icon-plus icon-black"></i></a>';
											echo "<br>";
										}
										else
										{
											 echo '&nbsp;<a href="javascript:void(0);" title="Remove" class="btn btn-success remove_row"><i class="icon-minus icon-black"></i></a>';
										
										}
										
										$j++;
										
									}
								}
							
							}
							else
							{
								$enter=1;
								echo "<span class='break_time'></span><span class='break_to_clone'>";
								$br_in =array("name"=>"break[in_time][".$day_value."][]","value"=>'00:00:00',"class"=>'in_break input-small break',"disabled"=>"disabled");
								echo form_input($br_in);
								$br_out = array("name"=>"break[out_time][".$day_value."][]","value"=>'00:00:00',"class"=>"out_break input-small","disabled"=>"disabled");
								echo form_input($br_out);
								echo "</span>";
								echo  '&nbsp;<a href="javascript:void(0);" title="Add" class="btn btn-danger add_row"><i class="icon-plus icon-black"></i></a>';
							
							}
						}
						else
						{
							$enter=1;
							echo "<span class='break_time'></span><span class='break_to_clone'>";
							$br_in =array("name"=>"break[in_time][".$day_value."][]","value"=>'00:00:00',"class"=>'in_break input-small',"disabled"=>"disabled");
							echo form_input($br_in);
							$br_out = array("name"=>"break[out_time][".$day_value."][]","value"=>'00:00:00',"class"=>"out_break input-small","disabled"=>"disabled");
							echo form_input($br_out);
							echo "</span>";
							echo  '&nbsp;<a href="javascript:void(0);" title="Add" class="btn btn-danger add_row"><i class="icon-plus icon-black"></i></a>';
						
						}
						if($enter==0)
						{
							echo "<span class='break_time'></span><span class='break_to_clone'>";
							$br_in =array("name"=>"break[in_time][".$day_value."][]","value"=>'00:00:00',"class"=>'in_break input-small' ,"disabled"=>"disabled");
							echo form_input($br_in);
							$br_out = array("name"=>"break[out_time][".$day_value."][]","value"=>'00:00:00',"class"=>"out_break input-small","disabled"=>"disabled");
							echo form_input($br_out);
							echo "</span>";
							echo  '</span>&nbsp;<a href="javascript:void(0);" title="Add" class="btn btn-danger add_row"><i class="icon-plus icon-black"></i></a>';
						}
						?>
                        <?php if(isset($shift["lunch"][0])) {?>
                        <input type="hidden" class="lunch_in" value="<?=$shift["lunch"][0]["from_time"]?>" />
                        <input type="hidden" class="lunch_out" value="<?=$shift["lunch"][0]["to_time"]?>" />
                        <?php }?>
                        <?php if(isset($shift["break"][0])) {?>
                        <input type="hidden" class="break_first_in" value="<?=$shift["break"][0]["from_time"]?>" />
                        <input type="hidden" class="break_first_out" value="<?=$shift["break"][0]["to_time"]?>" />
                        <?php }?>
                        <?php if(isset($shift["break"][1])) {?>
                        <input type="hidden" class="break_second_in" value="<?=$shift["break"][0]["from_time"]?>" />
                        <input type="hidden" class="break_second_in" value="<?=$shift["break"][0]["to_time"]?>" />
                        <?php }?>
                    </td>
                     <?php   
						
							echo "<td class='ot_class overtime ".$style."'>";
							
							echo "<span class='overtime_val'>";
							if($i==1)
							{
								if($overtimestart!=0)
								{
									/*echo $overtimestart;
									echo $overtimeend;*/
									
									if($attendance[$day_value]["out"] !="" && $attendance[$day_value]["out"] !="00:00:00")
									{
										
										$over1 = explode(':',$overtimestart);
										$over2 = explode(':',$overtimeend);
										$att_out = explode(':',$attendance[$day_value]["out"]);
									
										$ot_start=$attendance[$day_value]["attendance_date"]." ".$overtimestart;
										$ot_end =$attendance[$day_value]["attendance_date"]." ".$overtimeend;
										$in_start = $attendance[$day_value]["attendance_date"]." ".$attendance[$day_value]["in"];
										$out_end = $attendance[$day_value]["attendance_date"]." ".$attendance[$day_value]["out"];
										
										if(strtotime($ot_end)<=strtotime($ot_start))
										{
											$exclude_date = new DateTime($ot_end.' +1 day');
											$ot_end =$exclude_date->format('d-m-Y')." ".$overtimeend;
										}
										
										if(strtotime($out_end)<=strtotime($in_start))
										{
											$exclude_date = new DateTime($out_end.' +1 day');
											$out_end =$exclude_date->format('d-m-Y')." ".$attendance[$day_value]["out"];
										}
										if(strtotime($ot_start)<strtotime($in_start))
										{
											$ot_start= $in_start;
										}
										$difference ="";
										if(strtotime($ot_start)<strtotime($out_end))
										{
											if(strtotime($ot_end)>strtotime($out_end))
											{
												$ot_start=new DateTime($ot_start);
												$out_end = new DateTime($out_end);
												$difference = dateTimeDiff($ot_start,$out_end);
												
											}
											else if(strtotime($ot_end)<=strtotime($out_end))
											{
												$ot_start=new DateTime($ot_start);
												$ot_end = new DateTime($ot_end);
												$difference = dateTimeDiff($ot_start,$ot_end);
													
												
											}
											
										}
										
										$over=$attendance[$day_value]["attendance_date"]." ".$overtimestart;
										$date3 = new DateTime($attendance[$day_value]["attendance_date"]." ".$attendance[$day_value]["out"]);
										$interval1 = dateTimeDiff($date3,$date2);
										
										$overtime_hours = 0 ;
										
										if($difference!="")
										{
										
											//print_r($difference);
												if($difference->h>0)
													$overtime_hours += $difference->h *60;
												if($difference->i>10)
													$overtime_hours += $difference->i;
												else
													$overtime_hours +=$difference->i/60;
											
										}
										//echo $overtime_hours;
										if($overtime_break>0)
										{
											$overtime_hours -=$overtime_break;
										}
										if($overtime_hours>0)
										{
											
											echo gmdate("H:i:s", ($overtime_hours * 60));
												
										}
									}	
								}
							echo "</span>";
							?>
							<input type="hidden" class= "overtimestart" value="<?=$overtimestart?>" />
                        	<input type="hidden" class="overtimeend" value="<?=$overtimeend?>" />
                            <?php 
							} 
							echo "</td>";
						?>
                        <td  class="center total_hours <?=$style?>">
							<span class="total_diff">
                            <?php
						if($i==1)
						{ 	
						
								if(	$time_exist ==1){
									if($hour<10)
										echo "0".$hour .":";
									else
										echo $hour .":";
									if($mint<10)
										echo "0".$mint;
									else
										echo $mint ;								
							if($regular_time_val - $worked_hrs > 0)
								$status = 'lop';
							}
						}
								?><span class="disp_attr"><?php
                                if($leave_type ==1)
									echo '<b>( HD )</b>';
								else if($leave_type == 3)
								{
									if($leave_arr[$day_value]['type']!="")
										echo "<b>(  P )</b>";
									
								}
								?> </span>
                                </span>
								<input class="total" type="hidden">
                               
                                </td>
                                   <?php if($user_status[0]['status']==1)
								 {
								 	if(in_array("attendance:edit_attendance",$user_role))
									{
								 ?>
                                 <td style="<?=$style?>" class="action center  change_icon  no-display <?=$style?>">
                                 	
                                 <?php 
								 if($i==1)
								 {
								 	$attendance_id = $attendance[$day_value]["id"];
								 }
								 else
								 {
								 	$attendance_id = $day_value;
								 
								 }
								 	$send=array();
								 	if(isset($attendance[$day_value])&& !empty($attendance[$day_value])) 
									{
										$send = $attendance[$day_value];
									}								 	
								 ?>
                                
                              <a href='javascript:void(0)' class="edit"><i class='icon icon-pencil no-display'></i></a>
                             <a href='javascript:void(0)' class="cancel btn btn-danger btn-rounded">Cancel</a>
                            
				
			<?php 			
				if($leave_type ==1)
					echo " Half day( " .date('H:i',strtotime($leave_arr[$day_value]['leave_from']))." - ".date('H:i',strtotime($leave_arr[$day_value]['leave_to']))." )";
				else if($leave_type == 3)
				{
					if($leave_arr[$day_value]['type']!="")
						echo " Permission( " .date('H:i',strtotime($leave_arr[$day_value]['leave_from']))." - ".date('H:i',strtotime($leave_arr[$day_value]['leave_to']))." )";
					
				}
				else if($leave_type==5)
				{
					echo " On-duty( ".$on_duty_text." )";
				}
			?>
			</td>
        <?php 
			 }
		   }
		?>
        <tr>
    <?php 
	    }
	  }
	}
	?>
		</tbody>
  </table>
  </div>        
  </div>
  <div class="button_right_align">
     	<?php              
					  $data = array(
					  'name'        => 'update',
					  'value'		=> 'Save',
					  'class'		=> 'btn btn-primary btn-rounded save',
					  'title'		=> 'Save'
					);
					
					 echo form_submit($data);?>
        <?php if($user_status[0]['status']==1 && !isset($_GET['us']) && in_array("leave:apply_leave",$user_role))
		{?>
       <a href="<?=$this->config->item('base_url')."attendance/leave/apply_leave/".$user_id."?page=1"?>" id="apply_btn" title="Apply leave"><input type="button" class="btn btn-success btn-rounded" value="Apply for Leave" /></a>
        <?php }?>
       <a href="<?=$this->config->item('base_url')."attendance/monthly_attendance"?>" title="Back" ><input type="button" class="btn btn-info btn-rounded" value="Back" id="back_btn" /></a>
       <a href="javascript:void(0)" id="print" title="Print"><input type="button" value="Print" class="btn btn-warning btn-rounded"/><i class="icon icon-print print_icon"></i></a>
    </div>
    
    </div>
<?php 
	 function dateTimeDiff($date1, $date2) 
	{

		$alt_diff = new stdClass();
		$alt_diff->y =  floor(abs($date1->format('U') - $date2->format('U')) / (60*60*24*365));
		$alt_diff->m =  floor((floor(abs($date1->format('U') - $date2->format('U')) / (60*60*24)) - ($alt_diff->y * 365))/30);
		$alt_diff->d =  floor(floor(abs($date1->format('U') - $date2->format('U')) / (60*60*24)) - ($alt_diff->y * 365) - ($alt_diff->m * 30));
		$alt_diff->h =  floor( floor(abs($date1->format('U') - $date2->format('U')) / (60*60)) - ($alt_diff->y * 365*24) - ($alt_diff->m * 30 * 24 )  - ($alt_diff->d * 24) );
		$alt_diff->i = floor( floor(abs($date1->format('U') - $date2->format('U')) / (60)) - ($alt_diff->y * 365*24*60) - ($alt_diff->m * 30 * 24 *60)  - ($alt_diff->d * 24 * 60) -  ($alt_diff->h * 60) );
		$alt_diff->s =  floor( floor(abs($date1->format('U') - $date2->format('U'))) - ($alt_diff->y * 365*24*60*60) - ($alt_diff->m * 30 * 24 *60*60)  - ($alt_diff->d * 24 * 60*60) -  ($alt_diff->h * 60*60) -  ($alt_diff->i * 60) );
		$alt_diff->invert =  (($date1->format('U') - $date2->format('U')) > 0)? 0 : 1 ;
	
		return $alt_diff;
	} 


?>
<script type="text/javascript">
	$(document).ready(function(){
	
		$(".add_row").hide();
		$(".remove_row").hide();
		$(".time_in").hide();
		$(".time_out").hide();
		$(".in_break").hide();
		$(".out_break").hide();
		$(".save").hide();
		$(".cancel").hide();
    	<?php 
		if($ot_enable == 0)
		{
	?>	
   			$(".ot_class").hide();
			colspan = $(".colspan_td").attr("colspan");
           colspan = parseInt(colspan)-1;
          // alert(colspan);
          
            $(".colspan_td").attr("colspan",colspan);
	<?php }?>    
	});
</script>
<script type="text/javascript">
	function removejscssfile(filename, filetype){
 var targetelement=(filetype=="js")? "script" : (filetype=="css")? "link" : "none" //determine element type to create nodelist from
 var targetattr=(filetype=="js")? "src" : (filetype=="css")? "href" : "none" //determine corresponding attribute to test for
 var allsuspects=document.getElementsByTagName(targetelement)
 for (var i=allsuspects.length; i>=0; i--){ //search backwards within nodelist for matching elements to remove
  if (allsuspects[i] && allsuspects[i].getAttribute(targetattr)!=null && allsuspects[i].getAttribute(targetattr).indexOf(filename)!=-1)
   allsuspects[i].parentNode.removeChild(allsuspects[i]) //remove element by calling parentNode.removeChild()
 }
}

removejscssfile("<?= $theme_path; ?>/css/jquery-ui-timepicker-addon.min.css", "css") 

removejscssfile("<?= $theme_path; ?>/js/jquery-ui-timepicker-addon.min.js", "js") 

</script>
<script type="text/javascript">
$("document").ready(function()
{

$("#print").unbind("click").click(function()
{


$(".holiday_class.break_td").each(function()
{

    if($(this).closest("tr").find("td").eq(3).find(".time_calc").html()=="") 
   {
     $(this).html("Holiday");
	
	 
   }
	
	/*if($(this).closest("tr").find("td").eq(3).find(".day_value,.time_in,.time_out,.shift_in_time,.shift_out_time").val()!="")
	{
	 
	  $(this).html("Holiday");
	
	}*/
	 
	  
      
});

window.print();


$(".break_td").html("");

});

});

</script>

 <link rel="stylesheet" href="<?=$theme_path?>/css/jquery-ui-theme.css" type="text/css" />
  <link rel="stylesheet" href="<?=$theme_path?>/css/jquery.ui.timepicker.css" type="text/css" />
    <script type="text/ecmascript" src="<?=$theme_path?>/js/jquery.ui.timepicker.js"></script>

