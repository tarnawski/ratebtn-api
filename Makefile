CWD:=$(shell dirname $(realpath $(lastword $(MAKEFILE_LIST))))

archive:
	composer archive --format=tar --file=ratebtn