# P1 data reader
# @copyright B. de Witte
# @since 12-02-2014

# needed libraries
import sys
import serial
import time
import MySQLdb as mysql

# connect with the p1 port
ser = serial.Serial()
ser.baudrate = 9600
ser.bytesize=serial.SEVENBITS
ser.parity=serial.PARITY_NONE
ser.stopbits=serial.STOPBITS_ONE
ser.xonxoff=1
ser.rtscts=0
ser.timeout=20
ser.port="/dev/ttyUSB0"

print("Reading the P1 port")

try:
 ser.open()
except:
 sys.exit("Error opening the P1 port")
 
# init
response = False
t1_usage = t2_usage = t1_restitution = t2_restitution = ""
rate = current_usage = current_restitution = gas_usage = ""

# loop though the data
while True:
 if ser.readable():
   data_raw = ser.readline()
   data = str(data_raw).strip()
  
   # start
   if data[0:1] == "/":
    response = True
    
   # fill the vars
   if response :
    if data[0:9] == "1-0:1.8.1":
     t1_usage = data[10:19]
    elif data[0:9] == "1-0:1.8.2":
     t2_usage = data[10:19]
    elif data[0:9] == "1-0:2.8.1":
     t1_restitution = data[10:19]
    elif data[0:9] == "1-0:2.8.2":
     t2_restitution = data[10:19]
    elif data[0:9] == "1-0:2.8.1":
     t1_restitution = data[10:19]
    elif data[0:11] == "0-0:96.14.0":
     rate = data[15:16]     
    elif data[0:9] == "1-0:1.7.0":
     current_usage = data[10:17]
    elif data[0:9] == "1-0:2.7.0":
     current_restitution = data[10:17]
    elif data[0:1] == "(":
     gas_usage = data[1:9]     
  
    # end
    if data[0:1] == "!":
     break;

# close the connection
try:
 ser.close()
except:
 print("Error closing the P1 port")
 
# save to mysql
if response:
 db = mysql.connect("localhost", "root", "", "logs")
 query = db.cursor()
 try:
  query.execute("""INSERT INTO p1_data (date_created, t1_usage, t2_usage, t1_restitution, t2_restitution, rate, current_usage, current_restitution, gas_usage)
   VALUES(%s,%s,%s,%s,%s,%s,%s,%s,%s)""", (time.strftime('%Y-%m-%d %H:%M:%S'), t1_usage, t2_usage, t1_restitution, t2_restitution, rate, current_usage, current_restitution, gas_usage))
  db.commit()
 except mysql.Error, e:
  db.rollback()
  print "Error database %s" %e
 
 # close the database
 db.close()

