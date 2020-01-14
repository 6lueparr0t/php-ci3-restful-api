FROM ubuntu:16.04

RUN sed -i 's/archive.ubuntu.com/mirror.kakao.com/g' /etc/apt/sources.list

RUN apt-get clean \
    && apt-get -y update \
    && apt-get install -y --no-install-recommends \
    locales \
    python-software-properties \
    software-properties-common \
    && locale-gen en_US.UTF-8 \
    && rm -rf /var/lib/apt/lists/*

ENV LANG en_US.UTF-8
ENV LANGUAGE en_US:en
ENV LC_ALL en_US.UTF-8

RUN add-apt-repository ppa:ondrej/php

RUN apt-get update \
    && apt-get install -y \
      tzdata \
      apache2 \
      php7.3 \
      php7.3-cli \
      libapache2-mod-php7.3 \
      php7.3-gd \
      php7.3-json \
      php7.3-curl \
      php7.3-mbstring \
      php7.3-mysql \
      php7.3-redis \
      php7.3-mongodb \
      php7.3-xml \
      php7.3-xsl \
      php7.3-zip \
      composer \
      vim \
	  curl \
	  wget \
      zsh \
      git \
      mysql-client \
      && rm -rf /var/lib/apt/lists/*

RUN wget https://github.com/robbyrussell/oh-my-zsh/raw/master/tools/install.sh -O - | zsh || true
RUN sed -i -- 's/robbyrussell/clean/g' /root/.zshrc

RUN ln -sf /usr/share/zoneinfo/Asia/Seoul /etc/localtime

COPY run /usr/local/bin/run
RUN chmod +x /usr/local/bin/run
RUN a2enmod rewrite

COPY . /var/www/html

EXPOSE 80 443

CMD ["/usr/local/bin/run" ]

ENTRYPOINT ["zsh"]
