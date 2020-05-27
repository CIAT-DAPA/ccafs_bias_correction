FROM php:7.4-apache

MAINTAINER Steven Sotelo <stevenbetancurt@hotmail.com>

ENV FOLDER_APP=/var/www/html/ 

COPY ./src/ ${FOLDER_APP}
COPY ./entrypoint.sh /entrypoint.sh

RUN apt-get update \
    && apt-get install -y --no-install-recommends \	  
        vim \
        r-base \
		r-base-dev \
        libnetcdf-dev \                
        python \
        python-dev \
        #python-pip \
        cdo \
    && rm -rf /var/lib/apt/lists/* \
    && mkdir /var/log/apache2/app \
    && chmod 0777 /var/log/apache2/app \
    && chmod +x /entrypoint.sh \
    && /entrypoint.sh \
    && rm /entrypoint.sh \
    && chown www-data:www-data /var/www/html/downloads

VOLUME [ ${FOLDER_APP}, "/mnt", "/var/log/apache2/app" ]

EXPOSE 80

# docker build -t stevensotelo/ccafs_backend:latest .
# docker run -p 8081:80  --name ccafs_backend  -d stevensotelo/ccafs_backend:latest
# docker run -p 8081:80 --name ccafs_backend -v /dapadfs/data_cluster_5:/mnt/data_cluster_5 -v /dapadfs/data_cluster_4:/mnt/data_cluster_4 -v /dapadfs/data_cluster_2:/mnt/data_cluster_2 -d stevensotelo/ccafs_backend:latest