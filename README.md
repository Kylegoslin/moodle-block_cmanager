<img src="pix/logo.png" align="right" />

Course Request Manager block for Moodle
=======================================

![PHP](https://img.shields.io/badge/PHP-v7.1%2F%20v7.2%2F%20v7.3%2F%20v7.4-blue.svg)
![Moodle](https://img.shields.io/badge/Moodle-v3.7%20to%20v3.11.x-orange.svg)
[![GitHub Issues](https://img.shields.io/github/issues/michael-milette/moodle-block_cmanager.svg)](https://github.com/michael-milette/moodle-block_cmanager/issues)
[![Contributions welcome](https://img.shields.io/badge/contributions-welcome-green.svg)](#contributing)
[![License](https://img.shields.io/badge/License-GPL%20v3-blue.svg)](#license)

# Table of Contents

- [Basic Overview](#basic-overview)
- [Requirements](#requirements)
- [Download Course Request Manager for Moodle](#download-course-request-manager-for-moodle)
- [Installation](#installation)
- [Usage](#usage)
- [Updating](#updating)
- [Uninstallation](#uninstallation)
- [Limitations](#limitations)
- [Language Support](#language-support)
- [Troubleshooting](#troubleshooting)
- [Frequently Asked Questions (FAQ)](#faq)
- [Contributing](#contributing)
- [Motivation for this plugin](#motivation-for-this-plugin)
- [Further information](#further-information)
- [License](#license)

# Basic Overview

This block is allows Moodle administrators to streamline the course creation process by allowing educators to request courses to be created.

Once requested, the Moodle administrator is responsible for accepting or denying requests for courses. If the administrator is not satisfied with the request, a comment feature allows a conversation to take place between them and the educator to discuss the pending request.

During the process of creating a course request, additional information about the course can be collected from the educator such as course title, and other course information. This block offers additional customizable fields to allow the administrator to collect additional course
information.

Note: This plugin was recently adopted. This version is currently undergoing testing.

[(Back to top)](#table-of-contents)

# Requirements

This plugin requires Moodle 3.7+ from https://moodle.org/ .

[(Back to top)](#table-of-contents)

# Download Course Request Manager for Moodle

The most recent STABLE release of Course Request Manager for Moodle is available from:
https://moodle.org/plugins/block_cmanager

The most recent DEVELOPMENT release can be found at:
https://github.com/michael-milette/moodle-block_cmanager

[(Back to top)](#table-of-contents)

# Installation

Install the plugin, like any other plugin, to the following folder:

    /blocks/cmanager

See https://docs.moodle.org/en/Installing_plugins for details on installing Moodle plugins.

In order for the block to work, the block must be installed.

To add the block, navigate back to your site frontpage and add an instance of the block. You can add the block elsewhere but we recommend the site frontpage.

Ensure that you enter the configuration settings for the block (visible from the block) and configure the request block for new users.

You will also ned to set permissions for accessing the block and making requests etc. See the section on permissions for more information on this.

For more assistance please check out the plugin page on moodle.org

## Permissions

We’re making use of the Moodle permissions system. The block assumes that the following user roles are in existence:

* teacher
* coursecreator
* editingteacher
* manager
* student
* guest

When the bock is installed, users groups have the following permissions

* Admin: Complete control over the block including config and managing of requests etc
* Manager: Managing of requests - cannot update the config of the block
* Course Creator: Managing of requests - cannot update the config of the block
* Teacher: Those with a site level Teacher role can request courses. By default the teacher role is not a system role.
* Student: Can view the block but cannot access any functions (this may be disabled in the block permissions)
* Guest: Can view the block but cannot access any functions (this may be disabled in the block permissions)

You can either:

Allocate users who you want to be able to request courses the ‘Teacher’ role at the site level. **Some sites do this - we don't**

OR use the recommended approach:

1. Navigate to **Site Administration > Users > Permissions > Define Roles > Add new role** and create a new site role called **Course Requestor**.
2. Base the role on Authenticated User
3. Allow the role to be assigned at System level (or block level)
4. Grant the following permissions for Block: Course Request Manager
   * Add comment (block/cmanager:addcomment)
   * Add Record (Add Record)
   * Delete Record (block/cmanager:deleterecord)
   * Edit Record (block/cmanager:editrecord)
   * View Record (block/cmanager:viewrecord)
5. Assign some users to this new role.

Thats it! You should be good to go.

You can also remove or add permissions to the manager and course creator roles using define permissions.

[(Back to top)](#table-of-contents)

# Usage

[Documentation for the latest release of Course Request Manager block](https://docs.moodle.org/en/blocks/cmanager/) is available online.

[(Back to top)](#table-of-contents)

# Updating

There are no special considerations required for updating the plugin.

TODO: The first public ALPHA version was released on 2017-07-07, BETA on 2017-11-11 and STABLE as of 2018-11-26.

For more information on releases since then, see [CHANGELOG.md](https://github.com/michael-milette/moodle-block_cmanager/blob/master/CHANGELOG.md).

If you have any upgrade issues, it is best to remove the block, uninstall and reinstall. Just make sure all requests have been approved and the request history is not that valuable etc (as the old tables are dropped).

[(Back to top)](#table-of-contents)

# Uninstallation

Uninstalling the plugin by going into the following:

Home > Administration > Site Administration > Plugins > Manage plugins > Course Request Manager

...and click Uninstall. You may also need to manually delete the following folder:

    /blocks/cmanager

Note that, once uninstalled, any pending requests and the history will no longer be available.

[(Back to top)](#table-of-contents)

# Limitations

There are no known limitations at this time.

# Language Support

This plugin includes support for the English language.

If you need a different language that is not yet supported, please feel free to contribute using the Moodle [AMOS Translation Toolkit for Moodle](https://lang.moodle.org/).

This plugin has not been tested for right-to-left (RTL) language support. If you want to use this plugin with a RTL language and it doesn't work as-is, feel free to prepare a pull request and submit it to the project page at:

https://github.com/michael-milette/moodle-block_cmanager

# Troubleshooting

If the plugin does not seem to work properly for you, please ensure that you completed the [Permissions](#permissions) section of this README.md file.

More helpful information can be found in the [FAQ](#faq) below.

# FAQ
## Answers to Frequently Asked Questions

### Are there any security considerations?

There are no known security considerations at this time.

### How can I get answers to other questions?

Got a burning question that is not covered here? If you can't find your answer? Submit your question in the Moodle forums or open a new issue on Github at:

https://github.com/michael-milette/moodle-block_cmanaer/issues

[(Back to top)](#table-of-contents)

# Contributing

If you are interested in helping, please take a look at our [contributing](https://github.com/michael-milette/moodle-block_cmanager/blob/master/CONTRIBUTING.md) guidelines for details on our code of conduct and the process for submitting pull requests to us.

[(Back to top)](#table-of-contents)

## Contributors

Michael Milette, TNG Consulting Inc. - Lead Maintainer.

Big thank you to the following contributors. (Please let me know if I forgot to include you in the list):

* Kyle Goslin & Daniel McSweeney, Institute of Technology Blanchardstown, authors of the original [Course Request Manager for Moodle plugin](https://github.com/Kylegoslin/moodle-block_cmanager).
* Daniel Kearnan, Government of Canada
* Jeya Prakash
* Nicholas Stefanski

Thank you also to all the people who have requested features, tested and reported bugs.

[(Back to top)](#table-of-contents)

# Motivation for this plugin

The development of this plugin was motivated through our own experience in Moodle development, features requested by out clients and topics discussed in the Moodle forums. The project is sponsored and supported by TNG Consulting Inc.

[(Back to top)](#table-of-contents)

# Further Information

For further information regarding the enrol_invitation plugin, support or to report a bug, please visit the project page at:

https://github.com/michael-milette/moodle-enrol_invitation

[(Back to top)](#table-of-contents)

# License

Copyright © 2021-2022 TNG Consulting Inc. - https://www.tngconsulting.ca/
Copyright © 2012-2018 Kyle Goslin & Daniel McSweeney - Institute of Technology Blanchardstown

This file is part of Course Request Manager for Moodle - https://moodle.org/

Course Request Manager is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

Course Request Manager is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with Course Request Manager.  If not, see <https://www.gnu.org/licenses/>.

[(Back to top)](#table-of-contents)
