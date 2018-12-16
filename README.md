[![Docker Pulls](https://img.shields.io/docker/pulls/zaherg/simplecast-downloader.svg)](https://hub.docker.com/r/zaherg/simplecast-downloader/) [![](https://images.microbadger.com/badges/image/zaherg/simplecast-downloader.svg)](https://microbadger.com/images/zaherg/simplecast-downloader "Get your own image badge on microbadger.com") [![](https://images.microbadger.com/badges/version/zaherg/simplecast-downloader.svg)](https://microbadger.com/images/zaherg/simplecast-downloader "Get your own version badge on microbadger.com")



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

You can start using the script using Docker by:

1. Pulling the image from Docker hub
2. Run the image with Environment variable and Shared storage and execute the script

So lets start.

First we need to build the image:

```
docker build -t zaherg/simplecast-downloader .
docker pull zaherg/simplecast-downloader
```

Second we should run the script:

```
docker run -it --rm --name simplecast \
           --volume <your_local_path>:/app/backup \
           zaherg/simplecast-downloader:latest download --key=<your_api_key> --id=<your_podcast_id>
```

__NOTE__: When using docker, no need to setup a backup directory environment variable, as the data will be stored within the image and using the volume you can access it.
