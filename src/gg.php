<?php

require_once dirname(__FILE__) . "/../vendor/autoload.php";

$dir = dirname(__FILE__);

if (count($argv) < 2) {
    echo "\e[1m\e[32mGitGo\e[0m\n";
    echo "\e[3mThe one line Git pusher.\e[0m\n";
} else if (count($argv) === 2) {
    echo "\e[1m\e[32mGitGo\e[0m\n";
    echo "\e[1mChecking current project is a Git repository...\e[0m\n";

    $isGitPath = $dir . "/../bin/scripts/is_git.sh";
    exec("sh $isGitPath", $output, $return);

    if (count($output) && $output[0] === "true") {
        $output = array();
        echo "Current project is a Git repository.\n";
        exec("sh $dir/../bin/scripts/status.sh $argv[1]", $output, $return);

        if (count($output)) {
            foreach ($output as $line) {
                if ($line === "nothing to commit, working tree clean") {
                    echo "\n\e[1mNothing to commit, working tree clean.\e[0m\n";
                    exit(0);
                }
            }

            echo "\e[3m[1/3] Staging changes...\e[0m\n";
            exec("sh $dir/../bin/scripts/add.sh", $output, $return);

            echo "\e[3m[2/3] Committing changes...\e[0m\n";
            exec("sh $dir/../bin/scripts/commit.sh \"$argv[1]\"", $output, $return);

            echo "\e[3m[3/3] Pushing changes...\e[0m\n";
            exec("sh $dir/../bin/scripts/push.sh", $output, $return);
        }
    } else {
        echo "\e[3mCurrent project is not a Git repository. Run \e[1mgit init\e[0m\e[3m to create your repo.\e[0m\n";
    }
} else {
    echo "\e[1m\e[32mGitGo\e[0m\n";
    echo "\e[1;31mError:\e[0m you have entered an invalid number of arguments.\n";
}

