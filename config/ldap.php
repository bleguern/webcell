<?php
// Serveur LDAP (controleurs de domaine Active Directory)
$ldap['host'][0] = '192.168.1.22';         // srvadmlt01.modling.net
$ldap['host'][1] = '192.168.50.22';        // srvadlam01.modling.net
$ldap['host'][2] = '192.168.51.22';        // srvadtre01.modling.net

// Port du serveur LDAP
$ldap['port'] = 389;

// Domaine LDAP
$ldap['base_dn'] = 'dc=modling,dc=net';             // modling.net
?>
