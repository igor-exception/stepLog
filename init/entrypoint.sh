#!/bin/bash
set -e

# instala dependencias composer
/tmp/start_composer.sh

# executa o processo principal do docker
exec "$@"