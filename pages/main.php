<?php
	session_start();
	include_once(dirname(__FILE__).'/../util/auth.php');
	include_once(dirname(__FILE__).'/../util/sql.php');
	include_once(dirname(__FILE__).'/../util/util.php');
?>	
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<HTML>
<HEAD>
	<TITLE>ELBA - Appli Cellules</TITLE>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
	<link href="../css/style.css" rel="stylesheet" type="text/css">
</HEAD>
<BODY class="main_body">
<TABLE>
	<TR>
		<TD width="770">
			<TABLE>
				<TR valign="top" align="left" height="100%">
					<TD width="360" height="180">
						<TABLE>
							<tr>
								<TD>
									<TABLE>
										<TR>
											<td width="50" height="1" background="../image/menu/menu_separator_left.jpg" style="background-repeat:no-repeat "></td>
											<td width="260" height="1" background="../image/menu/pix.jpg"></td>
											<td width="50" height="1" background="../image/menu/menu_separator_right.jpg" style="background-repeat:no-repeat "></td>
										</TR>
									</TABLE>
								</TD>
							</tr>
							<tr>
								<td height="178" valign="top">
									<TABLE>
										<tr>
											<td height="5"></td>
										</tr>
										<tr>
											<td class="main_section_text">Affichage</td>
										</tr>
										<tr>
											<td height="5"></td>
										</tr>
										<tr>
											<td class="menu" background="../image/menu/separator.jpg" style="background-repeat:no-repeat;background-position:center"></td>
										</tr>
										<tr>
											<td height="5"></td>
										</tr>
										<tr>
											<td width="350">
												<TABLE>
													<TR>
														<TD>
															<TABLE>
																<TR>
																	<TD class="main_sub_section_text" width="286">- <A HREF="show/to_produce_time.php?history=/webcell/pages/main.php" TARGET="_self">Manque &agrave; produire</A> -</TD>
																	<TD class="field_separator"></TD>
																	<TD class="main_image"><A HREF="show/to_produce_time.php?history=/webcell/pages/main.php" TARGET="_self"><img src="../image/view_little.jpg" alt="Affichage du manque &agrave; produire"></A></TD>
																	<TD class="field_separator"></TD>
																	<TD class="main_image"><A HREF="export/to_produce_time.php" TARGET="_self"><img src="../image/excel_little.jpg" alt="Exportation Excel du manque &agrave; produire"></A></TD>
																	<TD class="field_separator"></TD>
																	<TD class="main_image"><A HREF="print/to_produce_time.php" TARGET="_self"><img src="../image/printer_little.jpg" alt="Impression du manque &agrave; produire"></A></TD>
																</TR>
															</TABLE>
														</TD>
													</TR>
													<TR>
														<TD class="min_separator"></TD>
													</TR>
													<TR>
														<TD>
															<TABLE>
																<TR>
																	<TD class="main_sub_section_text" width="286">- <A HREF="show/late_order.php?history=/webcell/pages/main.php" TARGET="_self">Commandes en retard</A> -</TD>
																	<TD class="field_separator"></TD>
																	<TD class="main_image"><A HREF="show/late_order.php?history=/webcell/pages/main.php" TARGET="_self"><img src="../image/view_little.jpg" alt="Affichage de la liste des commandes en retard"></A></TD>
																	<TD class="field_separator"></TD>
																	<TD class="main_image"><A HREF="export/late_order.php" TARGET="_self"><img src="../image/excel_little.jpg" alt="Exportation de la liste des commandes en retard"></A></TD>
																	<TD class="field_separator"></TD>
																	<TD class="main_image"><A HREF="print/late_order.php" TARGET="_self"><img src="../image/printer_little.jpg" alt="Impression de la liste des commandes en retard"></A></TD>
																</TR>
															</TABLE>
														</TD>
													</TR>
													<TR>
														<TD class="min_separator"></TD>
													</TR>
													<TR>
														<TD>
															<TABLE>
																<TR>
																	<TD class="main_sub_section_text" width="286">- <A HREF="show/order_dc.php?history=/webcell/pages/main.php" TARGET="_self">Commandes CDC</A> -</TD>
																	<TD class="field_separator"></TD>
																	<TD class="main_image"><A HREF="show/order_dc.php?history=/webcell/pages/main.php" TARGET="_self"><img src="../image/view_little.jpg" alt="Affichage des commandes CDC"></A></TD>
																	<TD class="field_separator"></TD>
																	<TD class="main_image"><A HREF="export/order_dc.php" TARGET="_self"><img src="../image/excel_little.jpg" alt="Exportation Excel des commandes CDC"></A></TD>
																	<TD class="field_separator"></TD>
																	<TD class="main_image"><A HREF="print/order_dc.php" TARGET="_self"><img src="../image/printer_little.jpg" alt="Impression des commandes CDC"></A></TD>
																</TR>
															</TABLE>
														</TD>
													</TR>
													<TR>
														<TD class="min_separator"></TD>
													</TR>
													<TR>
														<TD>
															<TABLE>
																<TR>
																	<TD class="main_sub_section_text" width="286">- <A HREF="show/order_bu.php?history=/webcell/pages/main.php" TARGET="_self">Commandes en exp&eacute;dition directe</A> -</TD>
																	<TD class="field_separator"></TD>
																	<TD class="main_image"><A HREF="show/order_bu.php?history=/webcell/pages/main.php" TARGET="_self"><img src="../image/view_little.jpg" alt="Affichage des commandes en expedition directe"></A></TD>
																	<TD class="field_separator"></TD>
																	<TD class="main_image"><A HREF="export/order_bu.php" TARGET="_self"><img src="../image/excel_little.jpg" alt="Exportation Excel des commandes en expedition directe"></A></TD>
																	<TD class="field_separator"></TD>
																	<TD class="main_image"><A HREF="print/order_bu.php" TARGET="_self"><img src="../image/printer_little.jpg" alt="Impression des commandes en expedition directe"></A></TD>
																</TR>
															</TABLE>
														</TD>
													</TR>
													<TR>
														<TD class="min_separator"></TD>
													</TR>
													<TR>
														<TD>
															<TABLE>
																<TR>	
																	<TD class="main_sub_section_text" width="286">- <A HREF="show/stock.php?history=/webcell/pages/main.php" TARGET="_self">Stocks</A> -</TD>
																	<TD class="field_separator"></TD>
																	<TD class="main_image"><A HREF="show/stock.php?history=/webcell/pages/main.php" TARGET="_self"><img src="../image/view_little.jpg" alt="Affichage des stocks"></A></TD>
																	<TD class="field_separator"></TD>
																	<TD class="main_image"><A HREF="export/stock.php" TARGET="_self"><img src="../image/excel_little.jpg" alt="Exportation Excel des stocks"></A></TD>
																	<TD class="field_separator"></TD>
																	<TD class="main_image"><A HREF="print/stock.php" TARGET="_self"><img src="../image/printer_little.jpg" alt="Impression des stocks"></A></TD>
																</TR>
															</TABLE>
														</TD>
													</TR>
													<TR>
														<TD class="min_separator"></TD>
													</TR>
<?php

if (isset($_SESSION['site_id']) && ($_SESSION['site_trigram'] == 'MOD')) {

?>
													<TR>
														<TD>
															<TABLE>
																<TR>
																	<TD class="main_sub_section_text" width="286">- <A HREF="show/out_of_stock.php?history=/webcell/pages/main.php" TARGET="_self">Transferts inter-d&eacute;p&ocirc;t</A> -</TD>
																	<TD class="field_separator"></TD>
																	<TD class="main_image"><A HREF="show/out_of_stock.php?history=/webcell/pages/main.php" TARGET="_self"><img src="../image/view_little.jpg" alt="Affichage des transferts inter-depot"></A></TD>
																	<TD class="field_separator"></TD>
																	<TD class="main_image"></TD>
																	<TD class="field_separator"></TD>
																	<TD class="main_image"></TD>
																</TR>
															</TABLE>
														</TD>
													</TR>
													<TR>
														<TD class="min_separator"></TD>
													</TR>
<?php

}

?>
												</table>
											</td>
										</TR>
									</table>
								</TD>
							</tr>
						</TABLE>
					</TD>
					<TD width="5" height="180">&nbsp;</TD>
					<TD width="200" height="180">
						<TABLE>
							<tr>
								<TD>
									<TABLE>
										<TR>
											<td width="50" height="1" background="../image/menu/menu_separator_left.jpg" style="background-repeat:no-repeat "></td>
											<td width="100" height="1" background="../image/menu/pix.jpg"></td>
											<td width="50" height="1" background="../image/menu/menu_separator_right.jpg" style="background-repeat:no-repeat "></td>
										</TR>
									</TABLE>
								</TD>
							</tr>
							<tr>
								<td height="178" valign="top">
									<TABLE>
										<tr>
											<td height="5"></td>
										</tr>
										<tr>
											<td class="main_section_text">Liste des commandes</td>
										</tr>
										<tr>
											<td height="5"></td>
										</tr>
										<tr>
											<td class="menu" background="../image/menu/menu_separator.jpg" style="background-repeat:no-repeat;background-position:center"></td>
										</tr>
										<tr>
											<td height="5"></td>
										</tr>
										<tr>
											<td width="200">
												<TABLE>
													<TR>
														<TD>
															<TABLE>
																<TR>
																	<TD class="main_sub_section_text" width="177">- <A HREF="show/custords_information.php?history=/webcell/pages/main.php" TARGET="_self">Par num&eacute;ro</A> -</TD>
																	<TD class="field_separator"></TD>
																	<TD class="main_image"><A HREF="show/custords_information.php" TARGET="_self"><img src="../image/view_little.jpg" alt="Affichage des informations par num&eacute;ro de commande"></A></TD>
																</TR>
															</TABLE>
														</TD>
													</TR>
													<TR>
														<TD class="min_separator"></TD>
													</TR>
													<TR>
														<TD>
															<TABLE>
																<TR>
																	<TD class="main_sub_section_text" width="177">- <A HREF="show/product_information.php" TARGET="_self">Par produit</A> -</TD>
																	<TD class="field_separator"></TD>
																	<TD class="main_image"><A HREF="show/product_information.php?history=/webcell/pages/main.php" TARGET="_self"><img src="../image/view_little.jpg" alt="Affichage des informations par article"></A></TD>
																</TR>
															</TABLE>
														</TD>
													</TR>
													<TR>
														<TD class="min_separator"></TD>
													</TR>
													<TR>
														<TD>
															<TABLE>
																<TR>
																	<TD class="main_sub_section_text" width="177">- <A HREF="show/customer_information.php?history=/webcell/pages/main.php" TARGET="_self">Par client</A> -</TD>
																	<TD class="field_separator"></TD>
																	<TD class="main_image"><A HREF="show/customer_information.php?history=/webcell/pages/main.php" TARGET="_self"><img src="../image/view_little.jpg" alt="Affichage des informations par client"></A></TD>
																</TR>
															</TABLE>
														</TD>
													</TR>
													<TR>
														<TD class="min_separator"></TD>
													</TR>
													<TR>
														<TD>
															<TABLE>
																<TR>
																	<TD class="main_sub_section_text" width="177">- <A HREF="show/cell_information.php?history=/webcell/pages/main.php" TARGET="_self">Par cellule</A> -</TD>
																	<TD class="field_separator"></TD>
																	<TD class="main_image"><A HREF="show/cell_information.php?history=/webcell/pages/main.php" TARGET="_self"><img src="../image/view_little.jpg" alt="Affichage des informations par cellule"></A></TD>
																</TR>
															</TABLE>
														</TD>
													</TR>
													<TR>
														<TD class="min_separator"></TD>
													</TR>
													<TR>
														<TD>
															<TABLE>
																<TR>
																	<TD class="main_sub_section_text" width="177">- <A HREF="show/machine_information.php?history=/webcell/pages/main.php" TARGET="_self">Par machine</A> -</TD>
																	<TD class="field_separator"></TD>
																	<TD class="main_image"><A HREF="show/machine_information.php?history=/webcell/pages/main.php" TARGET="_self"><img src="../image/view_little.jpg" alt="Affichage des informations par machine"></A></TD>
																</TR>
															</TABLE>
														</TD>
													</TR>
													<TR>
														<TD class="min_separator"></TD>
													</TR>
													<TR>
														<TD>
															<TABLE>
																<TR>
																	<TD class="main_sub_section_text" width="177">- <A HREF="show/sales_admin_information.php?history=/webcell/pages/main.php" TARGET="_self">Par ADV</A> -</TD>
																	<TD class="field_separator"></TD>
																	<TD class="main_image"><A HREF="show/sales_admin_information.php?history=/webcell/pages/main.php" TARGET="_self"><img src="../image/view_little.jpg" alt="Affichage des informations par ADV"></A></TD>
																</TR>
															</TABLE>
														</TD>
													</TR>
												</table>
											</td>
										</TR>
									</table>
								</TD>
							</tr>
						</TABLE>
					</TD>
<?php

if (isset($_SESSION['site_id'])) {
	if (($_SESSION['site_id'] != 100) && ( $_SESSION['site_trigram'] != 'NEG')) {
	
?>
					<TD width="5" height="180">&nbsp;</TD>
					<TD width="200" height="180">
						<TABLE>
							<tr>
								<TD>
									<TABLE>
										<TR>
											<td width="50" height="1" background="../image/menu/menu_separator_left.jpg" style="background-repeat:no-repeat "></td>
											<td width="100" height="1" background="../image/menu/pix.jpg"></td>
											<td width="50" height="1" background="../image/menu/menu_separator_right.jpg" style="background-repeat:no-repeat "></td>
										</TR>
									</TABLE>
								</TD>
							</tr>
							<tr>
								<td height="178" width="200" valign="top">
									<TABLE>
										<tr>
											<td height="5"></td>
										</tr>
										<tr>
											<td class="main_section_text">Planning board</td>
										</tr>
										<tr>
											<td height="5"></td>
										</tr>
										<tr>
											<td class="menu" background="../image/menu/menu_separator.jpg" style="background-repeat:no-repeat;background-position:center"></td>
										</tr>
										<tr>
											<td height="5"></td>
										</tr>
										<tr>
											<td width="200">
												<TABLE>
													<TR>
														<TD>
															<TABLE>
																<TR>
																	<TD class="main_sub_section_text" width="177">- <A HREF="show/planning_board_by_cell.php?history=/webcell/pages/main.php" TARGET="_self">Par cellule</A> -</TD>
																	<TD class="field_separator"></TD>
																	<TD class="main_image"><A HREF="show/planning_board_by_cell.php?history=/webcell/pages/main.php" TARGET="_self"><img src="../image/view_little.jpg" alt="Planning board par cellule"></A></TD>
																</TR>
															</TABLE>
														</TD>
													</TR>
													<TR>
														<TD class="min_separator"></TD>
													</TR>
													<TR>
														<TD>
															<TABLE>
																<TR>
																	<TD class="main_sub_section_text" width="177">- <A HREF="show/planning_board_by_machine.php?history=/webcell/pages/main.php" TARGET="_self">Par machine</A> -</TD>
																	<TD class="field_separator"></TD>
																	<TD class="main_image"><A HREF="show/planning_board_by_machine.php?history=/webcell/pages/main.php" TARGET="_self"><img src="../image/view_little.jpg" alt="Planning board par machine"></A></TD>
																</TR>
															</TABLE>
														</TD>
													</TR>
													<TR>
														<TD class="min_separator"></TD>
													</TR>
													<TR>
														<TD>
															<TABLE>
																<TR>
																	<TD class="main_sub_section_text" width="177">- <A HREF="show/planning_board_charge.php?history=/webcell/pages/main.php" TARGET="_self">R&eacute;capitulatif de charge</A> -</TD>
																	<TD class="field_separator"></TD>
																	<TD class="main_image"><A HREF="show/planning_board_charge.php?history=/webcell/pages/main.php" TARGET="_self"><img src="../image/view_little.jpg" alt="Recapitulatif charge"></A></TD>
																</TR>
															</TABLE>
														</TD>
													</TR>
												</table>
											</td>
										</TR>
									</table>
								</TD>
							</tr>
						</TABLE>
					</TD>
<?php

	}
}

?>
					<TD></TD>
				</TR>
			</TABLE>
		</TD>
	</TR>
	<TR>
		<TD class="separator"></TD>
	</TR>
	<TR valign="top" align="left">
		<TD width="770" valign="top" align="left">
			<TABLE>
				<TR valign="top" align="left">
					<TD width="465" align="left">
						<TABLE>
							<tr>
								<TD>
									<TABLE>
										<TR>
											<td width="50" height="1" background="../image/menu/menu_separator_left.jpg" style="background-repeat:no-repeat "></td>
											<td width="365" height="1" background="../image/menu/pix.jpg"></td>
											<td width="50" height="1" background="../image/menu/menu_separator_right.jpg" style="background-repeat:no-repeat "></td>
										</TR>
									</TABLE>
								</TD>
							</tr>
							<tr>
								<td>
									<TABLE>
										<tr>
											<td height="5"></td>
										</tr>
										<tr>
											<td class="main_section_text">Liste des messages</td>
										</tr>
										<tr>
											<td height="5"></td>
										</tr>
										<tr>
											<td class="menu" background="../image/menu/separator.jpg" style="background-repeat:no-repeat;background-position:center"></td>
										</tr>
										<tr>
											<td width="461">
												<iframe src="message/index.php" width="461" height="200" name="main" frameborder="0"></iframe>
											</td>
										</TR>
									</table>
								</TD>
							</tr>
							<tr>
								<TD>
									<TABLE>
										<TR>
											<td width="50" height="1" background="../image/menu/menu_separator_left.jpg" style="background-repeat:no-repeat "></td>
											<td width="365" height="1" background="../image/menu/pix.jpg"></td>
											<td width="50" height="1" background="../image/menu/menu_separator_right.jpg" style="background-repeat:no-repeat "></td>
										</TR>
									</TABLE>
								</TD>
							</tr>
						</TABLE>
					</TD>
					<TD width="5"></TD>
					<TD width="300">
						<TABLE>
							<tr>
								<TD>
									<TABLE>
										<TR>
											<td width="50" height="1" background="../image/menu/menu_separator_left.jpg" style="background-repeat:no-repeat "></td>
											<td width="200" height="1" background="../image/menu/pix.jpg"></td>
											<td width="50" height="1" background="../image/menu/menu_separator_right.jpg" style="background-repeat:no-repeat "></td>
										</TR>
									</TABLE>
								</TD>
							</tr>
							<tr>
								<td>
									<TABLE>
										<tr>
											<td height="5"></td>
										</tr>
										<tr>
											<td class="main_section_text">Liste des versions</td>
										</tr>
										<tr>
											<td height="5"></td>
										</tr>
										<tr>
											<td class="menu" background="../image/menu/menu_separator.jpg" style="background-repeat:no-repeat;background-position:center"></td>
										</tr>
										<tr>
											<td width="300">
												<iframe src="version/index.php" width="296" height="200" name="main" frameborder="0"></iframe>
											</td>
										</TR>
									</table>
								</TD>
							</tr>
							<tr>
								<TD>
									<TABLE>
										<TR>
											<td width="50" height="1" background="../image/menu/menu_separator_left.jpg" style="background-repeat:no-repeat "></td>
											<td width="200" height="1" background="../image/menu/pix.jpg"></td>
											<td width="50" height="1" background="../image/menu/menu_separator_right.jpg" style="background-repeat:no-repeat "></td>
										</TR>
									</TABLE>
								</TD>
							</tr>
						</TABLE>
					</TD>
				</TR>
			</TABLE>
		</TD>
	</TR>
</TABLE>
</BODY>
</HTML>