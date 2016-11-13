<?php

/*
 * This program is free software: you can redistribute it and/or modify it under
 * the terms of the GNU General Public License as published by the Free Software
 * Foundation, either version 3 of the License, or (at your option) any later
 * version.
 * 
 * This program is distributed in the hope that it will be useful, but WITHOUT
 * ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS
 * FOR A PARTICULAR PURPOSE. See the GNU General Public License for more
 * details.
 * 
 * You should have received a copy of the GNU General Public License along with
 * this program. If not, see <http://www.gnu.org/licenses/>.
 */

/**
 * PayPal System - IPN Coins
 * @author Dasoldier, Mary
 */

require "../class/paypal.class.php";
require "../config.php";
require "../common.php";

$p = new paypal_class;
$p->paypal_url = $payPalURL;

if ($p->validate_ipn())
{
	if ($p->ipn_data['payment_status']=='Completed') 
	{
		// Gets the donated amount
		$amount = $p->ipn_data['mc_gross'] - $p->ipn_data['mc_fee'];
		
		// Get character name from paypal ipn data
		$custom = $p->ipn_data['custom'];
                
                
                //ATTEMPT TO REWRITE THE WHOLE DAMN THING USING THE PDO API

                $querySaveDonations = $db->query("INSERT INTO log_paypal_donations (transaction_id,donation,amount,character_name) VALUES
		(
			'" . esc($p->ipn_data['txn_id']) . "',
			'Paypal, Coins',
			" . (float) $amount . ",
			'" . esc($custom) . "'
		)")->execute();
               
		$getamount = $p->ipn_data['mc_gross'];
		$totalPoints = $getamount + 00;
                
                
                //GET THE CHARACTER NAME AND FETCH ALL THE REQUIRED DATA (PDO)
                $queryCharName = $db->query("SELECT charId, account_name, online FROM characters WHERE char_name='".$custom."'");

                while ($charInfo = $queryCharName->fetchAll(PDO::FETCH_ASSOC)) {

                $charId = $charInfo['charId'];
                $account = $charInfo['account_name'];
                $charIsOnline = $charInfo['online'];
                
                }
                
                //results

		// Donate Rewards Mall Points
		if ($getamount == $mall_points)
		{
			if ($total>0)
			{
				//checks if the character is online
				if ($charIsOnline == 1)
				{
					// if character is online lets send some telnet commands
					include "l2j_telnet.php";
					
					//Telnet host, port, pass, timeout
					$telnet = new telnet("".$telnet_host."", "".$telnet_port."", "".$telnet_pass."", 2);
					
					$telnet->init();
                                        echo $telnet->write("addShopPoint ".$custom." ".$mall_points."");
					echo $telnet->write("quit");
				}
				else
				{
					// if player is offline we will add the items trough a mysql query
                                        $addOfflinePoints = $db->query("UPDATE accounts SET game_points=game_points+$mall_points WHERE login='".$account."';")->execute();

				}
			}
		}
		
