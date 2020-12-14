# K_COVID19_PROJECT

# 2020년 2학기 데이터베이스 수업 프로젝트

# 프로젝트 개요

### 프로젝트 기간 11/4 ~ 12/1
### mysql, python, php, javascript, Kakao Map APi

### 1.K_COVID19.csv 데이터를 parsing하여 가공해서 새로운 데이터테이블을 가진 csv로 만듭니다.
### 2. 데이터베이스 설계를 통해 원하는 정보를 가진 테이블을 생성하고 이를 mysql에 저장한다.
### 3. php, javascript로 웹페이지를 구현하고 카카오 MAP API를 통해 해당 정보를 지도에 표시한다.


## 1.팀 정보
![team-역할](https://user-images.githubusercontent.com/50866506/102108134-e3dded00-3e75-11eb-8e0f-c135cdefbd68.JPG)

## 2.테이블 설계 및 sql 실행화면

### 2-1.region_info (지역정보)
![region2](https://user-images.githubusercontent.com/50866506/102109151-176d4700-3e77-11eb-9fb9-162daedc49bd.png)

## Regioninfo 테이블
### 기준 attribute : province
### 이유 : province(지역) attribute를 구분하여 선택한 지역의 통계 정보를 알아보기 위함
### 2-2.timeageinfo (연령별정보)
![timeageinfo](https://user-images.githubusercontent.com/50866506/102106813-5f3e9f00-3e74-11eb-947b-a2e1351a45b0.JPG)
![timeageinfo](https://user-images.githubusercontent.com/50866506/102103319-34524c00-3e70-11eb-839d-56997a1d2e86.PNG)

### 2-3.timegenderinfo (성별정보)
![timegenderinfo](https://user-images.githubusercontent.com/50866506/102106816-606fcc00-3e74-11eb-827a-2a1948b09836.JPG)
![timegenderinfo](https://user-images.githubusercontent.com/50866506/102103322-34eae280-3e70-11eb-82a7-06e541cee69b.PNG)

### 2-4.timeprovinceinfo (도시별정보)
![timeprovinceinfo](https://user-images.githubusercontent.com/50866506/102106818-606fcc00-3e74-11eb-84ea-b44489f034b4.JPG)
![timeprovinceinfo](https://user-images.githubusercontent.com/50866506/102103326-34eae280-3e70-11eb-8647-0845378d0d54.PNG)
### 2-5. weatherinfo (날짜정보)
![weather2](https://user-images.githubusercontent.com/50866506/102109162-1a683780-3e77-11eb-8a6f-997ece819c38.png)
## Weatherinfo 테이블
### 기준 attribute : avg_tmp
### 이유 : avg_tmp(평균 온도) attribute를 구간을 나누어 지역과 날짜 정보를 추출해보기 위함
### 2-6. caseinfo (증상별정보)
![case2](https://user-images.githubusercontent.com/50866506/102109126-10463900-3e77-11eb-96e3-9959265ffd50.png)
##  Caseinfo 테이블
### 기준 attribute : infection_group
### 이유 : 감염 case가 집단감염인 경우와 집단 감염이 아닌 경우를 구분해서 알아보기 위함

## 3.웹페이지 구현 및 Map API 화면

### 3-1.환자정보가 들어있는 초기화면
![patient3(초기화면)](https://user-images.githubusercontent.com/50866506/102103882-dffb9c00-3e70-11eb-9d40-5a1cbfdaa514.png)

## Patientinfo 테이블 
### attribute : age
### age(연령) attribute가 K_COVID19에 영향을 끼치는지에 대해 알아보기 위해 연령별 환자정보를 추출함


### 3-2.특정 병원정보를 검색한 화면
![patient3(검색)](https://user-images.githubusercontent.com/50866506/102103757-b773a200-3e70-11eb-8994-bc0901307ef4.png)

### 병원정보=24 Hospital_ID=24로 검색한 결과 화면

### 3-3.카카오 Map API를 통해 병원정보를 표시한 화면
![map api](https://user-images.githubusercontent.com/50866506/102108775-aa59b180-3e76-11eb-96ff-a711016e0228.JPG)


