# Запуск

docker-compose up -d --build

docker exec -it bravo-be bash

php yii migrate

php yii seeder/add-user 

php yii seeder/translators 

php yii seeder/leads

# Страницы 

http://localhost:8083/translators

http://localhost:8083/leads