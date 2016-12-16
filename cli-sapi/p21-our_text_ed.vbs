Set WinScriptHost = CreateObject("WScript.Shell")

WinScriptHost.Run Chr(34) & "c:\Program Files (x86)\PHP\php-win.exe" & Chr(34) & " -e c:\Users\me\text_editor.php", 0

Set WinScriptHost = Nothing
