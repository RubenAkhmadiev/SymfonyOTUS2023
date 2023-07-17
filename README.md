# Market App

### Установка

```bash
cp docker-compose.override.yml.dist docker-compose.override.yml
docker-compose up --build -d
make install
make fixtures
```

|            | host                     | login          | password |
|------------|--------------------------|----------------|----------|
| backoffice | http://localhost/admin   | admin@site.com | password |
| graphql    | http://localhost/graphql | ?              | ?        |
