<?php
	require_once('vendor/autoload.php');

	$hosts = array('yahoo' => '{imap.mail.yahoo.com:993/imap/ssl/novalidate-cert}', 'gmail' => '{imap.gmail.com:993/imap/ssl/novalidate-cert}', 'outlook' => '{outlook.office365.com:993/imap/ssl/novalidate-cert}', 'hotmail' => '{pop3.live.com:995/pop3/ssl/novalidate-cert}');

	$hostoption = array();
	$x = 1;
	foreach ($hosts as $key => $value) 
	{
		echo $x . ". " . $key . PHP_EOL;
		$hostoption[] = $key;
		$x++;
	}
	echo "Masukkan pilihan (Hanya angka) : ";
	$selecthost = rtrim(fgets(STDIN))-1;
	$config['host'] = $hosts[$hostoption[$selecthost]];
	echo "Masukkan alamat email Anda : ";
	$config['email'] = rtrim(fgets(STDIN));
	echo "Masukkan password email Anda : ";
	$config['password'] = rtrim(fgets(STDIN));
	$config['dir_attachments'] = FALSE; //false means not save attachments. Replace with directory which you want to save the attachments

	echo "Trying to connect to server and login..." . PHP_EOL;
	$mailbox = new PhpImap\Mailbox($config['host'], $config['email'], $config['password'], $config['dir_attachments']);

	$folderoptions = array();
	$folders = $mailbox->getMailboxes();
	$x = 1;
	echo "Fetching all mailbox which available..." . PHP_EOL;
	foreach ($folders as $value) 
	{
		echo $x . ". " . $value['shortpath'] . PHP_EOL;
		$folderoptions[] = $value['fullpath'];
		$x++;
	}

	echo "Select folder you want (Just type the number) : ";
	$select = rtrim(fgets(STDIN))-1;
	$mailbox->switchMailbox($folderoptions[$select]);
	echo "Folder " . $folderoptions[$select] . " selected." . PHP_EOL;
	echo "Fetching all email from this mailbox..." . PHP_EOL;

	$mailsIds = $mailbox->searchMailbox();
	if($mailsIds)
	{
		$total = count($mailsIds);
		echo "Done fetching all email...".PHP_EOL;
		echo "Total email in this folder is {$total}" . PHP_EOL;
		echo "Deleting email in folder you choose..." . PHP_EOL;
		$x = 1;
		foreach ($mailsIds as $mailId) 
		{
	    	$mailbox->deleteMail($mailId);
	    	echo "[{$x}/{$total}] - Success deleting email...".PHP_EOL;
	    	$x++;
		}
	}
	else
	{
		echo "Mailbox is empty!" . PHP_EOL;
	}

	echo "DONE...";

	$mailbox->disconnect();
	
?>


