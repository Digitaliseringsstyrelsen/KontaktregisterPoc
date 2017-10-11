#!/bin/sh

STR_FILE_NAME="bruger_20000.txt";

echo "Loading....";
wc -l $STR_FILE_NAME;

echo "Cleaning.";

sed -i 's/\x0//g' $STR_FILE_NAME;
sed -i 's/[^a-zA-Z0-9]//g' $STR_FILE_NAME;
sed -i 's/\xFFFD//g' $STR_FILE_NAME;
sed -i 's/\s+//g' $STR_FILE_NAME;

echo "Done.";

wc -l $STR_FILE_NAME;
