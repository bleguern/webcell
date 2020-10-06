<?php
        include_once(dirname(__FILE__).'/../../util/sql.php');
        include_once(dirname(__FILE__).'/../../util/util.php');

function export_stock_to_excel() {

        $xls_output = '<TABLE class="data" border=\'0\' name=\'stock\' cellpadding=\'3\' cellspacing=\'1\' style=\'background-color:#d9d9d9\'>
                        <TR>
                                <TD><b>Reference</b></TD>
                                <TD><b>Description</b></TD>
                                <TD><b>Site</b></TD>
                                <TD><b>Quantite</b></TD>
                        </TR>';
		
        $sql = 'SELECT product.reference, product.name as description, site.trigram as site, SUM(stock.quantity) as quantity ' .
               'FROM product ' .
			   'LEFT OUTER JOIN site ON product.site_id = site.id ' .
			   'LEFT OUTER JOIN stock ON stock.product_id = product.id ' .
			   'WHERE product.reference IN (\'674417-01\', \'994034-20\', \'148639-20\', \'148939-20\', \'15143801-40\', \'15163801-40\', \'15163851-40\', \'15193801-40\', \'181020-20\', \'45260901-20\', \'45280901-20\', \'45300901-20\', \'45081951-20\', \'45380951-20\', \'45360951-20\', \'112306-20\', \'199906-20\', \'120406-20\', \'121519-20\', \'120306-20\', \'121316-20\', \'13353901-20\', \'134539-20\', \'123639-20\', \'12363950-20\', \'480034-20\', \'480040-07\', \'43200901-20\', \'43191901-20\', \'120440-07\', \'43106901-20\', \'120360-20\', \'120460-20\', \'120418-20\', \'12041850-20\', \'43000901-20\', \'20093901-20\', \'01983801-40\', \'01973801-40\', \'43221901-20\', \'43225901-20\', \'43225903-20\', \'23983801-40\', \'44001901-20\', \'43005901-20\', \'43308901-20\', \'45000931-20\', \'45000932-20\', \'45040935-20\', \'45040952-20\', \'45060951-20\', \'45060952-20\', \'45200905-20\', \'199549-20\', \'19954901-20\', \'199950-20\', \'19995001-20\', \'19996001-20\', \'199960-20\', \'45200902-20\', \'45240901-20\', \'199840-20\', \'19984050-20\', \'42091901-20\', \'193540-07\', \'180529-20\', \'160529-20\', \'106629-20\', \'104629-20\', \'489640-07\', \'399725-20\', \'399740-20\', \'01953801-40\', \'01963801-40\', \'199038-20\', \'19903850-20\', \'210949-20\', \'02068961-20\', \'05380031-03\', \'190569-20\', \'700100-20\', \'700300-20\', \'700200-20\', \'43149931-20\', \'20993901-20\', \'20993950-20\', \'204238-40\', \'268040-07\', \'203319-06\', \'207019-20\', \'261590-20\', \'260393-20\', \'26039301-20\', \'463040-07\', \'203939-20\', \'40413801-40\', \'463440-07\', \'661601-20\', \'263034-20\', \'204039-20\', \'241601-20\', \'10468-20\', \'10464-20\', \'07025-06\', \'04024-06\', \'10454-20\', \'10458-20\', \'10476C-20\', \'207530-17\', \'263951-20\', \'263935-01\', \'191500-07\', \'51108901-20\', \'62683901-20\', \'62684-20\', \'62109901-20\', \'62101905-20\', \'62101902-20\', \'62102905-20\', \'62657901-20\', \'62677901-20\', \'66160150-20\', \'05379031-03\', \'463042-20\', \'208800-20\', \'60201901-20\', \'60151901-20\', \'294903-20\', \'294904-20\', \'60101905-20\', \'62649903-20\', \'60630901-20\', \'62629901-20\', \'62629904-17\', \'54951501-17\', \'62659903-20\', \'959042-20\', \'10479C-20\', \'260389-20\', \'02903801-40\', \'26008901-20\', \'26028901-20\', \'260289-20\', \'260189-20\', \'01903801-40\', \'01933801-40\', \'019938-20\', \'01993801-20\', \'1993850-20\', \'9039-20\', \'009040-20\', \'009339-20\', \'9532-20\', \'903703-20\', \'959040-07\', \'959034-20\', \'260080-20\', \'52302071-14\', \'52309071-14\', \'52310071-14\', \'52306071-14\', \'682889-20\', \'682890-20\', \'681690-20\', \'681689-20\', \'682689-20\', \'682690-20\', \'184007-20\', \'183002-20\', \'184002-20\', \'70501901-20\', \'903025-20\', \'903023-22\', \'903021-20\', \'903022-20\', \'902020-20\', \'90202501-20\', \'90202601-06\', \'903027-07\', \'902066-20\', \'90206650-20\', \'902076-20\', \'903002-20\', \'90300250-20\', \'09056081-17\', \'09057081-17\', \'09059081-17\', \'09060081-17\', \'09079081-17\', \'09084081-17\', \'09015501-17\', \'09016501-17\', \'09015502-17\', \'09016502-17\', \'62086941-21\', \'62082941-21\', \'62046943-21\', \'62042943-21\', \'557495-20\', \'62036941-21\', \'62032501-17\', \'62236502-17\', \'62236503-17\', \'05335501-17\', \'05336501-17\', \'05337501-17\', \'05338501-17\', \'172106-20\', \'558500-20\', \'558505-20\', \'558510-20\', \'559510-20\', \'559610-20\', \'559615-20\', \'577742-20\', \'552590-20\', \'558512-20\', \'577422-20\', \'577423-20\', \'577741-20\', \'578741-20\', \'528501-20\', \'699501-20\', \'518501-20\', \'519501-20\', \'20774103-19\', \'20742103-19\', \'207112-20\', \'207212-20\', \'175520-20\', \'175512-20\', \'578640-07\', \'578240-07\', \'172112-20\', \'275136-20\', \'172136-20\', \'2251961-20\', \'2250961-20\', \'09011081-17\', \'970060-20\', \'680515-17\', \'680615-20\', \'09081081-17\', \'09129081-17\', \'970549-17\', \'97054901-17\', \'289911-17\', \'02134081-17\', \'01956011-09\', \'02050081-17\', \'970028-17\', \'977759-17\', \'208050-17\', \'227510-17\', \'208040-17\', \'970050-20\', \'670619-17\', \'227530-17\', \'670071-17\', \'970519-17\', \'217274-17\', \'21727450-17\', \'2096730050-17\', \'20967303-17\', \'209676-17\', \'208773-17\', \'208873-17\', \'228873-17\', \'971889-20\', \'670410-17\', \'322144-20\', \'322142-20\', \'09066931-20\', \'09069-20\', \'09071-20\', \'053232-20\', \'322040-20\', \'322060-20\', \'32208001-20\', \'322100-20\', \'322120-20\', \'32403801-40\', \'32603801-40\', \'32813801-40\', \'32113801-40\', \'32123801-40\', \'32143801-40\', \'32163801-40\', \'32203801-40\', \'321038-40\', \'328238-40\', \'05373031-03\', \'05374031-03\', \'054034-20\', \'058034-20\', \'051034-20\', \'01545971-20\', \'01546971-20\', \'01747971-20\', \'09242931-20\', \'320222-20\', \'320260-20\', \'320161-20\', \'322110-20\', \'32211001-20\', \'322170-20\', \'32217050-20\', \'059034-20\', \'05903401-20\', \'320100-20\', \'320124-20\', \'056042-20\', \'056040-07\', \'322140-20\', \'323040-20\', \'320040-20\', \'320060-20\', \'320080-20\', \'320201-20\', \'320018-20\', \'320016-20\', \'320012-20\', \'320020-20\', \'320014-20\', \'320010-20\', \'324180-20\', \'002001-17\', \'00200150-17\', \'00200160-17\', \'100180-20\', \'100181-17\', \'100182-04\', \'100183-08\', \'100184-09\', \'100185-11\', \'100186-13\', \'100187-15\', \'100188-10\', \'100240-20\', \'100241-17\', \'100350-17\', \'200520-20\', \'200529-17\', \'210520-20\', \'54001-20\', \'200181-20\', \'10044002-20\', \'10044007-20\', \'10044104-17\', \'100190-20\', \'10025003-20\', \'100250-20\', \'100360-20\', \'200340-20\', \'210340-20\', \'100160-20\', \'100161-17\', \'100162-04\', \'100163-08\', \'100164-09\', \'100165-11\', \'100166-13\', \'100167-15\', \'100168-10\', \'100220-20\', \'10022002-20\', \'100221-17\', \'100330-17\', \'2013700202-20\', \'201370-20\', \'201379-17\', \'10042007-20\', \'100170-20\', \'10023003-20\', \'100230-20\', \'100340-20\', \'201390-20\', \'100140-20\', \'100141-17\', \'100142-04\', \'100143-08\', \'100144-09\', \'100145-11\', \'100146-13\', \'100147-15\', \'100148-10\', \'100200-20\', \'100201-17\', \'100310-17\', \'100311-20\', \'2015100202-20\', \'201510-20\', \'201519-17\', \'10040003-20\', \'10040007-20\', \'10040103-17\', \'100150-20\', \'10021003-20\', \'100210-20\', \'100320-20\', \'20153008-20\', \'201530-20\', \'10042003-20\', \'10042103-17\', \'203963-20\', \'202020-20\', \'202029-17\', \'202050-20\', \'202059-17\', \'202070-20\', \'202079-17\', \'20207950-17\', \'202089-17\', \'203159-17\', \'203559-17\', \'203659-17\', \'203750-20\', \'204050-20\', \'204059-17\', \'00528409-17\', \'40080502-20\', \'40080551-20\', \'593619-09\', \'593719-09\', \'562419-20\', \'562519-20\', \'58307901-20\', \'58312901-20\', \'58311-20\', \'58520941-21\', \'58521941-21\', \'58509941-21\', \'58509942-17\', \'58512941-21\', \'58306-20\', \'60107-17\', \'60113-17\', \'553529-20\', \'553629-20\', \'080858-20\', \'080958-20\', \'080258-20\', \'080158-20\', \'081058-20\', \'086058-20\', \'809778-20\', \'808678-20\', \'809779-20\', \'808679-20\', \'05984108-20\', \'05964108-20\', \'05924108-20\', \'059041-20\', \'05904108-20\', \'05934108-20\', \'05944108-20\', \'05974108-20\', \'05954108-20\', \'059141-20\', \'05914108-20\', \'090858-20\', \'090158-20\', \'091058-20\', \'096058-20\', \'090458-20\', \'809725-20\', \'808625-20\', \'809025-20\', \'800527-20\', \'800727-20\', \'809927-20\', \'809827-20\', \'808327-20\', \'809727-20\', \'800627-20\', \'806027-20\', \'806927-20\', \'809728-20\', \'808628-20\', \'888628-20\', \'807628-20\', \'800744-20\', \'809744-20\', \'800044-20\', \'805644-20\', \'808644-20\', \'809044-20\', \'800644-20\', \'800444-20\', \'800544-20\', \'809745-20\', \'808645-20\', \'809347-20\', \'800547-20\', \'800747-20\', \'800047-20\', \'809747-20\', \'805647-20\', \'888647-20\', \'808647-20\', \'809047-20\', \'800647-20\', \'800447-20\', \'759416-20\', \'759716-20\', \'755616-20\', \'798616-20\', \'758616-20\', \'759016-20\', \'800515-20\', \'800715-20\', \'809915-20\', \'800015-20\', \'808315-20\', \'809015-20\', \'800615-20\', \'806015-20\', \'809754-20\', \'808654-20\', \'858516-20\', \'600591-20\', \'600791-20\', \'600691-20\', \'809791-20\', \'808691-20\', \'809333-20\', \'800533-20\', \'800733-20\', \'809733-20\', \'805633-20\', \'808633-20\', \'809033-20\', \'800633-20\', \'709395-20\', \'700595-20\', \'700795-20\', \'700695-20\', \'859795-20\', \'858695-20\', \'859095-20\', \'809312-20\', \'800512-20\', \'800712-20\', \'809712-20\', \'805612-20\', \'808612-20\', \'809012-20\', \'800612-20\', \'809736-20\', \'808636-20\', \'807636-20\', \'759418-20\', \'750518-20\', \'750718-20\', \'759718-20\', \'758618-20\', \'759018-20\', \'750618-20\', \'750418-20\', \'759717-20\', \'758617-20\', \'757617-20\', \'756817-20\', \'700553-20\', \'700753-20\', \'700653-20\', \'809753-20\', \'808653-20\', \'807653-20\', \'809751-20\', \'808651-20\', \'806851-20\', \'809952-20\', \'808352-20\', \'809052-20\', \'806052-20\', \'995534-20\', \'995542-20\', \'995545-20\', \'995540-07\', \'995558-20\', \'990058-20\', \'990035-20\', \'995528-20\', \'995544-20\', \'995547-20\', \'995554-20\', \'995533-20\', \'995517-20\', \'995553-20\', \'990016-20\', \'990018-20\', \'990033-20\', \'201604-20\', \'201605-20\', \'201606-20\') ' .
				'GROUP BY product.reference ' .
				'ORDER BY product.reference, site.trigram';

        $result = mysql_select_query($sql);

        if($result) {

                while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {

                        $xls_output .= '<TR style=\'background-color:#FFFFFF\' valign=\'center\'>
                                                <TD class="data">'.format_to_reference($row['reference']).'</TD>
                                                <TD class="data">'.$row['description'].'</TD>
                                                <TD class="data">'.$row['site'].'</TD>
                                                <TD class="number">'.format_to_number($row['quantity']).'</TD>
                                        </TR>';
                }
        }

        $xls_output .= '</TABLE>';
        return $xls_output;
}

session_cache_limiter("must-revalidate");
header("Content-type: application/vnd.ms-excel");
header("Content-disposition: attachment; filename=stock_".date("Ymd").".xls");

//session_start();
print export_stock_to_excel();
exit();
?>