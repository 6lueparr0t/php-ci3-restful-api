# Codeigniter를 이용한 RESTful API 개발

## API 사용법

### 1. 유저 조회

GET http://127.0.0.1:8080/v1/user?key=email&value=test@test.com
http://127.0.0.1:8080/v1/user


| 파라미터 | 데이터 |
| -- | -- |
|key | 검색할 키 |
|value| 검색할 값 |

ex) curl --location --request GET 'http://127.0.0.1:8080/v1/user?key=email&value=test@test.com'

### 2. 유저 생성

POST http://127.0.0.1:8080/v1/user


| 파라미터 | 데이터 |
| -- | -- |
| *name | 한글, 영문(대,소문자) |
| *nick | 영문(소문자) |
| *tel | 최대 20자 |
| *email | 최대 100자 |
| *pswd | 최소 10자 이상 |
| rcmd | 추천인 |
| gender | 성별 (m, f) |

ex) curl --location --request POST 'http://127.0.0.1:8080/v1/user' \
--data-raw '{"name":"testman", "nick":"testt", "tel":"01012345678", "email":"test@test.com", "pswd":"testtest!1", "rcmd":"", "gender":"m"}'

### 3. 유저 업데이트

PUT http://127.0.0.1:8080/v1/user


| 파라미터 | 데이터 |
| -- | -- |
|*request | 업데이트 값 (JSON 타입) |
|*key | 검색할 키 |
|*value| 검색할 값 |

ex) curl --location --request PUT 'http://127.0.0.1:8080/v1/user' \
--data-raw '{"request":{"name":"daihyun"}, "key":"idx", "value" : "9" }'

### 4. 유저 삭제

DEL http://127.0.0.1:8080/v1/user


| 파라미터 | 데이터 |
| -- | -- |
|*key | 검색할 키 |
|*value| 검색할 값 |

ex) curl --location --request DELETE 'http://127.0.0.1:8080/v1/user' \
--data-raw '{"key":"idx", "value" : "9" }'

### 5. 유저 리스트 (페이징)

GET http://127.0.0.1:8080/v1/userlist


| 파라미터 | 데이터 |
| -- | -- |
|*page | 숫자 |
|count| 숫자 (default:30) |

ex) curl --location --request GET 'http://127.0.0.1:8080/v1/userlist?page=0'
