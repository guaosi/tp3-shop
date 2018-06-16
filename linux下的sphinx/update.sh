#!/bin/sh
/usr/local/coreseek/bin/indexer -c  /usr/local/coreseek/etc/sphinx.conf   goods_del  --rotate
/usr/local/coreseek/bin/indexer -c  /usr/local/coreseek/etc/sphinx.conf --merge goods goods_del  --merge-dst-range is_updated 0 0 --rotate
/usr/local/coreseek/bin/indexer -c  /usr/local/coreseek/etc/sphinx.conf   goods_zl  --rotate
/usr/local/coreseek/bin/indexer -c  /usr/local/coreseek/etc/sphinx.conf --merge goods goods_zl --merge-dst-range is_updated 0 0 --rotate
