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

age_list = []

for age in list(data['age']):
    if age in age_list:
        continue
    else:
        if type(age) == str : #null값 제외
            age_list.append(age)

# Using Hashing
# get confirmed_date from "K_COVID19.csv" and count
age_temp = data['age']
confirmed_date = data['confirmed_date']
deceased_date = data['deceased_date']
size = len(age_temp)
cdate_dic = {}
ddate_dic = {}

for age in age_list :
    cdate_dic[age] = {}
    ddate_dic[age] = {}

for i in range(0,size):
    age, date = age_temp[i], confirmed_date[i]
    if age in cdate_dic.keys():
        if date in cdate_dic[age].keys() :
            cdate_dic[age][date] = cdate_dic[age][date] + 1
        else :
            cdate_dic[age][date] = 1

for i in range(0,size):
    age, date = age_temp[i],  deceased_date[i]
    if age in ddate_dic.keys():
        if date in ddate_dic[age].keys() :
            ddate_dic[age][date] = ddate_dic[age][date] + 1
        else :
            ddate_dic[age][date] = 1


# 중복된 case 제거를 위해 checking list & variable
date = []
total_confirmed = {}
total_deceased = {}
for age in age_list:
    total_confirmed[age] = 0
    total_deceased[age] = 0

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

        for age in age_list:
            sql_data=[]
            sql_data.append(line[0])
            if line[col_list['date']] in cdate_dic[age].keys():
                total_confirmed[age] = total_confirmed[age] + cdate_dic[age][line[col_list['date']]]
            if line[col_list['date']] in ddate_dic[age].keys():
                total_deceased[age] = total_deceased[age] + ddate_dic[age][line[col_list['date']]]
            sql_data.append(age) 
            sql_data.append(total_confirmed[age])
            sql_data.append(total_deceased[age])

            #Make query & execute
          
            sql_data = tuple(sql_data)
            print(sql_data)
            
            query = """INSERT INTO `timeageinfo`(date, age, confirmed, deceased) VALUES (%s,%s,%s,%s)"""
            #for debug
            try:
                cursor.execute(query, sql_data)
                print("[OK] Inserting [%s] to timeageinfo"%(line[col_list['date']]))
            except (pymysql.Error, pymysql.Warning) as e :
                # print("[Error]  %s"%(pymysql.IntegrityError))
                if e.args[0] == 1062: continue
                print('[Error] %s | %s'%(line[col_list['date']],e))
                break
            
conn.commit()
cursor.close()