<------------------------------------**STREAM TARGETS DIRECT (FILE) IMPORTING**------------------------------------------------------->
Prepare your streamtargets data into .sql file (Assuming you are working on your desktop)
***steps***
Prepare a csv file, say streamtargets.csv
Install pandas if you have not - On your cmd run 'pip install pandas'
Prepare a python executable file, say csv_to_sql.py and Write this script on it and save

            import pandas as pd
            file_path = r'C:\Users\User\Desktop\streamtargets.csv'  # Update with your actual path
            df = pd.read_csv(file_path, encoding='ISO-8859-1')      # Change to your actual encoding if needed
            print("Column names:", df.columns)                      #checks column names
            df.columns = df.columns.str.strip()                     # Strips whitespace from column names

            sql_statements = []
            for index, row in df.iterrows():
                sql = f"INSERT INTO streamtarget (streamtarget_id, streamtarget_ward_id, streamtarget_user_id, streamtarget_stream_id, streamtarget_amount, streamtarget_fy) " \
                    f"VALUES ({int(row['streamtarget_id'])}, {int(row['streamtarget_ward_id'])}, {int(row['streamtarget_user_id'])}, {int(row['streamtarget_stream_id'])}, {row['streamtarget_amount']}, {int(row['streamtarget_fy'])});"
                sql_statements.append(sql)                           # Generates SQL INSERT statements

            for statement in sql_statements:                         # Prints or save the SQL statements (optional)
                print(statement)

            with open('targets.sql', 'w') as f:                       # saves the prepared statements to a targets.sql file on your desktop
                for statement in sql_statements:
                    f.write(statement + '\n')

Run the script - on cmd navigate to the directory containing your .py file and run 'python csv_to_sql.py'
Your targets.sql file is ready
The targets.sql file is ready for uploading - Navigate RevCoRe to SysAdmins Targets setter, click the upload Targets Button
**Note:**    
The file name should be 'targets'   and its extension should be '.sql'   No other file type will be accepted.   
The file will be stored in ../storage/ and will be replaced any time a new targets.sql file is uploaded
<----------------------------------------------**Crafted by Arnold Mutua**------------------------------------------------------------>