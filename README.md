# checkdomains

This project lets you run `domain-check` (see https://github.com/saidutt46/domain-check) via a simple web interface that can be uploaded to any webserver or run locally. By that you do not rely on any external batch domain checker tools.

 <video width="320" height="240" controls>
  <source src="https://github.com/misteu/checkdomains/raw/refs/heads/main/demo.mp4" type="video/mp4">
Your browser does not support the video tag.
</video> 

## Attention

Please do not use this on production as this requires to give some elevated rights (at least for accessing domain-check) to the `www-data` user.

## How to run

1. Follow install instructions for `domain-check` (see https://github.com/saidutt46/domain-check) and check if the command line tool is working.
2. Upload `index.php` and `check.php` into your webserver's folder for hosting php scripts. In a lot of cases this is just somewhere in `/var/www/`. But this depends on your specific setup.
3. Access the `index.php` via any browser
4. Enter some domainname and hit "Check"
5. You will see streaming results coming in, grouped into "Available", "Unknown" and "Taken".
6. Hit "Stop" in case you want to cancel the requesting.

## Things to improve
Currently, the tool just searches for any TLD the `domain-check` tool knows, just using the `--all` parameter in the background.

For more granular searches a list of TLDs to check for might be reasonable. Feel free to open a PR :-)

## Disclaimer

The entire thing is 100% vibe coded, I asked ChatGPT and glued it all together until it worked.

## Attention again

Please don't use this anywhere on production. I basically just created this for usage via my homeserver that cannot be accessed from the outside.
