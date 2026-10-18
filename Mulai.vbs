Set fso = CreateObject("Scripting.FileSystemObject")
Set sh = CreateObject("WScript.Shell")
dir = fso.GetParentFolderName(WScript.ScriptFullName)
sh.CurrentDirectory = dir
sh.Run """" & dir & "\php\php.exe"" artisan serve --host=127.0.0.1 --port=8000", 0, False
WScript.Sleep 3000
sh.Run "http://127.0.0.1:8000"