# -*- coding: utf-8 -*- 
import pymysql
import csv
import pandas as pd

#mysql server 연결, port 및 host 주의!
conn = pymysql.connect(host='localhost',
                        port = 3306,
                        user='root', 
                        password='password', 
                        db='K_COVID19', 
                        charset='utf8')

# Connection 으로부터 Cursor 생성
cursor = conn.cursor()

data = pd.read_csv('./combine/K_COVID19.csv')

province_list = []

for province in list(data['province']):
    if province in province_list:
        continue
    else:
         if type(province) == str:
            province_list.append(province)

# Using Hashing
# get confirmed_date from "K_COVID19.csv" and count
province_temp = data['province']
confirmed_date = data['confirmed_date']
released_date = data['released_date']
deceased_date = data['deceased_date']
size = len(province_temp)
cdate_dic = {}
rdate_dic = {}
ddate_dic = {}

for province in province_list :
    cdate_dic[province] = {}
    rdate_dic[province] = {}
    ddate_dic[province] = {}

for i in range(0,size):
    province, date = province_temp[i], confirmed_date[i]
    if province in cdate_dic.keys():
        if date in cdate_dic[province].keys() :
            cdate_dic[province][date] = cdate_dic[province][date] + 1
        else :
            cdate_dic[province][date] = 1

for i in range(0,size):
    province, date = province_temp[i],  released_date[i]
    if province in rdate_dic.keys():
        if date in rdate_dic[province].keys() :
            rdate_dic[province][date] = rdate_dic[province][date] + 1
        else :
            rdate_dic[province][date] = 1

for i in range(0,size):
    province, date = province_temp[i],  deceased_date[i]
    if province in ddate_dic.keys():
        if date in ddate_dic[province].keys() :
            ddate_dic[province][date] = ddate_dic[province][date] + 1
        else :
            ddate_dic[province][date] = 1


# 중복된 case 제거를 위해 checking list & variable
date = []
total_confirmed = {}
total_released = {}
total_deceased = {}
for province in province_list:
    total_confirmed[province] = 0
    total_released[province] = 0
    total_deceased[province] = 0

with open("./combine/addtional_Timeinfo.csv", 'r') as file:
    file_read = csv.reader(file)

    # Use column 1(date)
    # index = column - 1
    col_list = { 
        'date' :0,
       }

    for i,line in enumerate(file_read):

        #Skip first line
        if not i:                           
            continue

        # checking duplicate case_id & checking case_id == "NULL"
        if (line[col_list['date']] in date) or (line[col_list['date']] == "NULL") :
            continue
        else:
            date.append(line[col_list['date']])

        #make sql data & query
        sql_data = []
        #"NULL" -> None (String -> null)
        for idx in col_list.values() :
            if line[idx] == "NULL" :
                line[idx] = None
            else:
                line[idx] = line[idx].strip()

        for province in province_list:
            sql_data=[]
            sql_data.append(line[0])
            if line[col_list['date']] in cdate_dic[province].keys():
                total_confirmed[province] = total_confirmed[province] + cdate_dic[province][line[col_list['date']]]
            if line[col_list['date']] in rdate_dic[province].keys():
                total_released[province] = total_released[province] + rdate_dic[province][line[col_list['date']]]
            if line[col_list['date']] in ddate_dic[province].keys():
                total_deceased[province] = total_deceased[province] + ddate_dic[province][line[col_list['date']]]
            sql_data.append(province) 
            sql_data.append(total_confirmed[province])
            sql_data.append(total_released[province])
            sql_data.append(total_deceased[province])

            #Make query & execute
          
            sql_data = tuple(sql_data)
            print(sql_data)
            
            query = """INSERT INTO `timeprovinceinfo`(date, province, confirmed, released, deceased) VALUES (%s,%s,%s,%s,%s)"""
            #for debug
            try:
                cursor.execute(query, sql_data)
                print("[OK] Inserting [%s] to timeprovinceinfo"%(line[col_list['date']]))
            except (pymysql.Error, pymysql.Warning) as e :
                # print("[Error]  %s"%(pymysql.IntegrityError))
                if e.args[0] == 1062: continue
                print('[Error] %s | %s'%(line[col_list['date']],e))
                break
            
conn.commit()
cursor.close()