#!/usr/bin/env bash

commit=$1
if [ -z ${commit} ]; then
    commit=$(git tag | tail -n 1)
    if [ -z ${commit} ]; then
        commit="master";
    fi
fi

# Build new release
mkdir -p WhoopsForShopware
git archive ${commit} | tar -x -C WhoopsForShopware
zip -r WhoopsForShopware-${commit}.zip WhoopsForShopware
