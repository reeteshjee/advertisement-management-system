<?php
// Process the incoming POST data
$data = file_get_contents('php://input');
var_dump($data);
$json = json_decode($data, true);
echo $json;


// Check the event type (push event in this case)
if (isset($json['ref']) && $json['ref'] == 'refs/heads/main') {
    pullFromGitHub();
}else{
	echo 'invalid request';
}
exit;


function pullFromGitHub(){

	$repoDirectory = '/home/reeteshg/sponsored.youthsforum.com';

	// Your GitHub personal access token (PAT)
	$githubToken = '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';  // Replace with your actual GitHub token

	// GitHub repository URL using HTTPS with the token
	$githubRepoUrl = "https://{$githubToken}:x-oauth-basic@github.com/reeteshjee/advertisement-management-system.git";

	// Change to the repository directory
	chdir($repoDirectory);

	// Run the git pull command (HTTPS with token authentication)
	$command = "git pull {$githubRepoUrl} main";  // Change "master" if you use a different branch
	$output = shell_exec($command . " 2>&1");  // Capture both stdout and stderr

	// Output the result
	echo '<pre>' . $output . '</pre>';
}


