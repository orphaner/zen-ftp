<?php

// Choose here the backend to use
// purepw : use virtual pureftpd users
// mysql : use a mysql server to store users
// pgsql : use a postgressql server to store users
define ('CFG_BACKEND', ''); // purepw || mysql || pgsql

// Configure database access
// only needed is the backend is mysq or pgsql
define ('CFG_DB_HOST', '');
define ('CFG_DB_LOGIN', '');
define ('CFG_DB_PASSWORD', '');
define ('CFG_DB_DATABASE', '');

// Define pure-pw files paths
// Only needed if the backend is purepw
define ('CFG_PW_PASSWD_FILE', '/etc/pure-ftpd/pureftpd.passwd');
define ('CFG_PW_PDB_FILE', '/etc/pure-ftpd/pureftpd.pdb');

// Define the default uid/gid
define ('CFG_DEFAULT_UID', 1002);
define ('CFG_DEFAULT_GID', 1002);

// This list of users will NOT appear in the dropdown menu.
$blacklistUsers = array ('adm','bin','bind','daemon','gopher','halt','kmem','lp',
                         'mailnull','man','named','nfsnobody','nscd','operator',
                         'pop','root','rpc','rpcuser','rpm','shutdown','smmsp',
                         'sshd','sync','toor','tty','uucp','vcsa','xfs');

// This list of groups will NOT appear in the dropdown menu.
$blacklistGroups = array ('adm','bin','bind','daemon','dialer','dip','disk','floppy','gopher','kmem',
                          'lock','lp','mailnull','man','named','mem','network','news',
                          'nscd','ntp','operator','pcap','root','rpc','rpcuser','rpm','slocate','smmsp',
                          'sshd','staff','sys','tty','utmp','uucp','vcsa','wheel','xfs');

// fr | en
define ('LANG', 'fr');

?>
