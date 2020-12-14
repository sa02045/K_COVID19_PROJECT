# -*- coding: utf-8 -*- 
import pymysql
import csv
import pandas as pd
import math

def euclidian_distance(x1,y1,x2,y2): #유클리디안 거리
    distance = pow((x1-x2),2) + pow((y1-y2),2)
    return math.sqrt(distance)

#mysql server 연결, port 및 host 주의!
conn = pymysql.connect(host='localhost',
                        port = 3306,
                        user='root', 
                        password='password', 
                        db='K_COVID19', 
                        charset='utf8')

# Connection 으로부터 Cursor 생성
cursor = conn.cursor()

data = pd.read_csv('./combine/Region.csv')
regioninfo = data[['province','city','latitude','longitude']].values
region_num = len(regioninfo)
region_location = {}
for i in range(region_num):
    if(regioninfo[i][0]==regioninfo[i][1]): #province == city
        region_location[regioninfo[i][0]] = [regioninfo[i][2],regioninfo[i][3]] #[latitude, longitude]

sql = "SELECT * FROM patientinfo"
cursor.execute(sql)
patientinfo = cursor.fetchall()
patientinfo = pd.DataFrame(patientinfo)
data_num = len(patientinfo)

# patient_id : 0, province : 4, city : 5
patientinfo = patientinfo[[0,4,5]].values
patient_location = {}
patient_num = 0
for i in range(data_num):
    patient_id = patientinfo[i][0]
    province = patientinfo[i][1]
    city = patientinfo[i][2]
    if (city is None or city == 'etc') :
        patient_location[patient_id]= [region_location[province][0], region_location[province][1]]
    else : #city 가 NULL이거나 etc가 아닌 경우 환자의 위치 정보
        sql = "SELECT latitude, longitude FROM regioninfo where province = \'%s\' and city = \'%s\'"%(province, city)
        cursor.execute(sql)
        location = cursor.fetchall()
        if not location : #patientinfo에 city가 존재하지만, 해당 city의 region_code가 NULL 값 인 city 제외
            continue
        latitude, longitude = location[0]
        patient_location[patient_id] = [latitude, longitude]
    patient_num = patient_num + 1
    
# 중복된 case 제거를 위해 checking list
sql_data_list = []
hospital_num = 0
hospital_id = []
with open("./combine/Hospital.csv", 'r',encoding = 'UTF8') as file:
    file_read = csv.reader(file)

    # Use column 1(Hospital_id), 2(Hospital_name), 3(Hospital_province), 4(Hospital_city), 5(Hospital_latitude), 6(Hospital_longitude), 7(capacity), 8(now)
    # index = column - 1
    col_list = { 
        'hospital_id' :0,
        'hospital_name' :1,
        'hospital_province' : 2,
        'hospital_city' : 3,
        'hospital_latitude' : 4,
        'hospital_longitude' : 5,
        'capacity' : 6,
        'now' : 7}

    for i,line in enumerate(file_read):
        #Skip first line
        if not i:                           
            continue

        # checking duplicate case_id & checking case_id == "NULL"
        if (line[col_list['hospital_id']] in hospital_id) or (line[col_list['hospital_id']] == "NULL") :
            continue
        else:
            hospital_id.append(line[col_list['hospital_id']])

        #make sql data & query
        sql_data = []

        #"NULL" -> None (String -> null)
        for idx in col_list.values() :
            if line[idx] == "NULL" :
                line[idx] = None
            else:
                line[idx] = line[idx].strip()

            sql_data.append(line[idx])
        sql_data_list.append(sql_data) 
        hospital_num=hospital_num+1

patient_info_hospital_id = {}
for patient_id in patient_location:
    
    a = {} 
    patient_latitude = float(patient_location[patient_id][0])
    patient_longitude = float(patient_location[patient_id][1])
    for j in range(hospital_num): 
        hospital_latitude = float(sql_data_list[j][4])
        hospital_longitude = float(sql_data_list[j][5])
        distance = euclidian_distance(patient_latitude, patient_longitude, hospital_latitude, hospital_longitude)
        a[j] = distance
    sorted_a = sorted(a.items(), key = lambda item: item[1])
    
   
    for k in range(hospital_num):
        select_hospital_id , select_distance = sorted_a[k]
        if(int(sql_data_list[select_hospital_id][6]) > int(sql_data_list[select_hospital_id][7])):
            sql_data_list[select_hospital_id][7] = str(int(sql_data_list[select_hospital_id][7]) + 1)
            patient_info_hospital_id[patient_id] = str(select_hospital_id)
            break
        else:
            continue #꽉 찬 경우 다음 병원 배정
    

#hospital_info insert
for sql_data in sql_data_list : #now값이 수정된 sql_data 저장
    query = """INSERT INTO `hospitalInfo`(hospital_id,hospital_name,hospital_province,hospital_city,hospital_latitude,hospital_longitude,capacity,now) VALUES (%s,%s,%s,%s,%s,%s,%s,%s)"""
    sql_data = tuple(sql_data)
    #print(sql_data)
    #for debug
    try:
        cursor.execute(query, sql_data)
        print("[OK] Inserting [%s] to hospitalInfo"%(sql_data[0]))
    except (pymysql.Error, pymysql.Warning) as e :
        # print("[Error]  %s"%(pymysql.IntegrityError))
        if e.args[0] == 1062: continue
        print('[Error] %s | %s'%(sql_data[0],e))
        break    

#patient_info update
for patient_id in patient_info_hospital_id :
    query = "UPDATE patientinfo SET hospital_id = \'%s\' where patient_id = \'%s\'"%(str(int(patient_info_hospital_id[patient_id])+1), patient_id)
    cursor.execute(query)

conn.commit()
cursor.close()
