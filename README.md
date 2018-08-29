# Simplecast Downloader

This simple script will give you the ability to export and download all your __personal__ Podcasts that you have uploaded to Simplecast.com

The main problem is that Simplecast does not provide a pause functionality, so if you ever decided to pause your show you have either:
 
1. To delete your account and reupload everything again
2. Or to keep paying so your data wont be deleted.

## Using the script

### Cloning the project

As a githubber I think you know how to clone the project

```
git clone https://github.com/linuxjuggler/simplecast-downloader.git
```

Then install the packages via composer

```
composer install
```

Lastly run the following commands:

```
export BACKUP_DIRECTORY=<the_path_for_your_backup_directory>
./simplecast download --key=<your_api_key> --id=<your_podcast_id>
``` 

And the script will start to download the files and store them within your backup directory. Inside your backup directory you will find 3 files:

1. Audio file: this is the mp3 podcast file
2. JSON file: this file will contain the meta information about this specific podcast.
3. Image file: this is the artwork file that you have used in your podcast, and this is the large version of it.


### Via Docker

To be added later.
