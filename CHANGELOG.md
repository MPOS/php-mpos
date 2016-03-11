1.0.5 (XXX XXth XXXX)
---------------------

* Fixed worker name scaling issues on mobile devices (Thanks @nrpatten)
* Fixed user information table formatting (Thanks @pokari1986)
* Fixed empty auto-payout threshold value for accounts page
* Removed config disable check popup for admins on all pages
* Added blockchain download status for admin feedback (admin setup check)
* Added peer state to wallet info state if no peers are connected

1.0.4 (Jun 19th 2015)
---------------------

* Honor anonymous attribute when sending block finder mails
* Display admin warning if no transfer fees are set
* Moved admin_checks.php into the admin panel/system/setup
 * Checks are now loaded individually from pages/admin/checks

1.0.3 (Apr 29th 2015)
---------------------

* HOTFIX: Database upgrade from `1.0.0` to `1.0.1` did not work as
  intended

1.0.2 (Apr 28th 2015)
---------------------

* Allow SSO accross MPOS pools
  * Added a new config options
    * `$config['db']['shared']['acounts']`, defaults to `$config['db']['name']`
    * `$config['db']['shared']['workers']`, defaults to `$config['db']['name']`
    * `$config['db']['shared']['news']`, defaults to `$config['db']['name']`
  * Will access `accounts`, `pool_workers` and `news` on shared table
  * Does not allow splitting `accounts` and `pool_woker` across database hosts
  * Required `$config['cookie']['domain']` to be set
    * You need to use the top domain shared between hosts as the setting
    * e.g. `ltc.thepool.com` and `btc.thepool.com` it has to be `.thepool.com` (NOTE the leading .)
* Increased information on `Admin -> Wallet Info`
  * Added block count to Wallet Status
  * Added number of accounts to Wallet Status
  * Added Peer information
  * Added last 25 transactions
    * Can be changed via Admin System Settings -> Wallet
  * Always show all accounts
* Updated Auto Payout Threshold to be stored in `coin_address` table
  * Existing thresholds will be migrated when upgrading
  * Update to `1.0.1` for the database using the upgrade script supplied in MPOS
* Updated Bootstrap to 3.3.4
* Updated MorrisJS to 0.5.1
* Updated RaphaelJS to 2.1.2
* Updated Bootstrap Switch to 3.3.2
* Updated CLEditor to 1.4.5
* Removed unneeded JS files
* Removed unneeded CSS files
* Fixed ding for block notifications not playing on Safari
* Fixed manual payout warning to show when account balance is too low

1.0.1 (Apr 15th 2015)
---------------------

* Updated jQuery and SoundJS
* Removed unneeded JS files

1.0.0 (Jan 18th 2015)
---------------------

* First (non-beta) public release of MPOS
